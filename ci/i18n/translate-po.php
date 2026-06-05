<?php

declare(strict_types=1);

/**
 * Fill the empty msgstr entries of a PO file by calling Claude headlessly.
 *
 * Translation is "assisted via Claude Code": this only ever sends the strings
 * that are still UNTRANSLATED (DRY — already-translated strings, including ones
 * carried over from the translation memory / a previous run, are never re-sent).
 * Input/output is JSON so quoting, placeholders and HTML survive intact.
 *
 * Usage:
 *   php translate-po.php <po-file> [--locale=xx_XX] [--batch=40] [--limit=N] [--model=sonnet]
 *
 * --limit is for smoke-testing (translate only the first N untranslated entries).
 */

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use Gettext\Translations;

/** @return array<string,string> */
function parseArgs(array $argv): array
{
    $opts = ['batch' => '40', 'limit' => '0', 'model' => 'sonnet', 'locale' => ''];
    $opts['file'] = '';
    foreach (array_slice($argv, 1) as $arg) {
        if (str_starts_with($arg, '--')) {
            [$k, $v] = array_pad(explode('=', substr($arg, 2), 2), 2, '1');
            $opts[$k] = $v;
        } else {
            $opts['file'] = $arg;
        }
    }
    return $opts;
}

function localeToLanguageName(string $locale): string
{
    $map = [
        'es_ES' => 'Spanish (Spain)',
        'fr_FR' => 'French (France)',
        'pt_BR' => 'Portuguese (Brazil)',
        'pt_PT' => 'Portuguese (Portugal)',
        'de_DE' => 'German (Germany)',
        'nl_NL' => 'Dutch (Netherlands)',
        'it_IT' => 'Italian (Italy)',
        'ja'    => 'Japanese',
        'ja_JP' => 'Japanese',
        'zh_CN' => 'Chinese (Simplified)',
        'zh_TW' => 'Chinese (Traditional)',
        'ru_RU' => 'Russian',
    ];
    return $map[$locale] ?? $locale;
}

/**
 * Call `claude -p` headlessly, piping the prompt via stdin, returning stdout.
 */
function callClaude(string $prompt, string $model): string
{
    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];
    $cmd = sprintf('claude -p --model %s --output-format text', escapeshellarg($model));
    $process = proc_open($cmd, $descriptors, $pipes);
    if (!is_resource($process)) {
        throw new RuntimeException('Failed to start claude');
    }
    fwrite($pipes[0], $prompt);
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $exit = proc_close($process);
    if ($exit !== 0) {
        throw new RuntimeException("claude exited {$exit}: {$stderr}");
    }
    return (string) $stdout;
}

/** Extract the first top-level JSON object from arbitrary model output. */
function extractJsonObject(string $text): ?array
{
    $text = trim($text);
    // strip code fences if present
    $text = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', $text);
    $start = strpos($text, '{');
    $end = strrpos($text, '}');
    if ($start === false || $end === false || $end < $start) {
        return null;
    }
    $decoded = json_decode(substr($text, $start, $end - $start + 1), true);
    return is_array($decoded) ? $decoded : null;
}

function buildPrompt(array $batch, string $language, string $locale): string
{
    $input = json_encode($batch, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return <<<PROMPT
        You are localizing the "Gato GraphQL" WordPress plugin (a GraphQL server for WordPress) into {$language} (locale {$locale}).

        Translate the VALUES of the JSON object below from English to {$language}. The keys are numeric ids — keep them exactly as-is.

        Return ONLY a JSON object with the same keys and the translated values. No commentary, no markdown, no code fences.

        Rules:
        - Preserve every placeholder verbatim: %s, %d, %1\$s, %2\$s, etc.
        - Preserve HTML tags and entities (<code>…</code>, <a href="…">, &amp;, etc.) and any Markdown.
        - Do NOT translate the contents of <code>…</code>, code identifiers, GraphQL keywords, field/type names, or URLs.
        - Preserve leading/trailing whitespace of each string.
        - Keep technical terms consistent and natural for a developer audience.

        {$input}
        PROMPT;
}

// --- main ---------------------------------------------------------------

$opts = parseArgs($argv);
$poFile = $opts['file'];
if ($poFile === '' || !is_file($poFile)) {
    fwrite(STDERR, "PO file not found: {$poFile}\n");
    exit(1);
}
$batchSize = max(1, (int) $opts['batch']);
$limit = (int) $opts['limit'];
$model = $opts['model'];

$locale = $opts['locale'];
if ($locale === '' && preg_match('/-([a-z]{2}(?:_[A-Z]{2})?)\.po$/', $poFile, $m)) {
    $locale = $m[1];
}
$language = localeToLanguageName($locale);

$translations = Translations::fromPoFile($poFile);

/** @var \Gettext\Translation[] $pending */
$pending = [];
foreach ($translations as $translation) {
    if ($translation->getOriginal() === '') {
        continue; // header
    }
    if ($translation->hasTranslation()) {
        continue; // already translated — never re-send (DRY)
    }
    if ($translation->getPlural() !== '') {
        continue; // plurals handled in a later pass; skip for now
    }
    $pending[] = $translation;
}

$totalUntranslated = count($pending);
if ($limit > 0) {
    $pending = array_slice($pending, 0, $limit);
}

if ($pending === []) {
    echo "Nothing to translate in {$poFile} ({$locale}).\n";
    exit(0);
}

printf(
    "Translating %d of %d untranslated entries in %s -> %s (batch %d)\n",
    count($pending),
    $totalUntranslated,
    basename($poFile),
    $locale,
    $batchSize
);

$done = 0;
foreach (array_chunk($pending, $batchSize) as $chunkIndex => $chunk) {
    $batch = [];
    foreach ($chunk as $i => $translation) {
        $batch[(string) ($i + 1)] = $translation->getOriginal();
    }
    $response = callClaude(buildPrompt($batch, $language, $locale), $model);
    $result = extractJsonObject($response);
    if ($result === null) {
        fwrite(STDERR, "  ! batch {$chunkIndex}: could not parse Claude output; skipping\n");
        continue;
    }
    foreach ($chunk as $i => $translation) {
        $key = (string) ($i + 1);
        if (isset($result[$key]) && $result[$key] !== '') {
            $translation->setTranslation((string) $result[$key]);
            $done++;
        }
    }
    // persist incrementally so a long run can be resumed
    $translations->toPoFile($poFile);
    printf("  batch %d: %d translated (%d total)\n", $chunkIndex + 1, count($chunk), $done);
}

printf("Done: %d entries translated in %s\n", $done, basename($poFile));
