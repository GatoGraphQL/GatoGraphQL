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
function callClaude(string $prompt, string $model, int $maxAttempts = 4): string
{
    $lastError = 'unknown error';
    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $cmd = sprintf('claude -p --model %s --output-format text', escapeshellarg($model));
        $process = proc_open($cmd, $descriptors, $pipes);
        if (!is_resource($process)) {
            $lastError = 'failed to start claude';
            sleep($attempt * 3);
            continue;
        }
        fwrite($pipes[0], $prompt);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exit = proc_close($process);
        if ($exit === 0 && trim((string) $stdout) !== '') {
            return (string) $stdout;
        }
        $lastError = "exit {$exit}: " . trim((string) $stderr);
        sleep($attempt * 5); // backoff before retrying a transient failure
    }
    throw new RuntimeException("claude failed after {$maxAttempts} attempts: {$lastError}");
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

function buildPluralPrompt(array $batch, string $language, string $locale, int $nplurals): string
{
    $input = json_encode($batch, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    return <<<PROMPT
        You are localizing the "Gato GraphQL" WordPress plugin (a GraphQL server for WordPress) into {$language} (locale {$locale}).

        Each entry below has an English "singular" and "plural" form. Translate them into {$language}, producing exactly {$nplurals} plural form(s) per entry, in the order this locale's gettext Plural-Forms expects.

        Return ONLY a JSON object with the same numeric keys, each mapping to an array of {$nplurals} translated string(s). No commentary, no markdown, no code fences.

        Rules: preserve every placeholder verbatim (%s, %d, %1\$s, ...), HTML tags/entities, the contents of <code>…</code>, and leading/trailing whitespace.

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
/** @var \Gettext\Translation[] $pendingPlural */
$pendingPlural = [];
foreach ($translations as $translation) {
    if ($translation->getOriginal() === '') {
        continue; // header
    }
    if ($translation->getPlural() !== '') {
        if (!$translation->hasTranslation()) {
            $pendingPlural[] = $translation;
        }
        continue;
    }
    if ($translation->hasTranslation()) {
        continue; // already translated — never re-send (DRY)
    }
    $pending[] = $translation;
}

$totalUntranslated = count($pending);
if ($limit > 0) {
    $pending = array_slice($pending, 0, $limit);
}

// nplurals for this locale (from the PO's Plural-Forms header; default 2)
$pluralForms = $translations->getPluralForms();
$nplurals = is_array($pluralForms) && isset($pluralForms[0]) ? (int) $pluralForms[0] : 2;

if ($pending === [] && $pendingPlural === []) {
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
    try {
        $response = callClaude(buildPrompt($batch, $language, $locale), $model);
    } catch (\RuntimeException $e) {
        fwrite(STDERR, "  ! batch {$chunkIndex}: {$e->getMessage()}; skipping (retry next run)\n");
        continue;
    }
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

// plural entries (skipped by --limit subsets, since they are few)
if ($limit === 0) {
    foreach (array_chunk($pendingPlural, $batchSize) as $chunkIndex => $chunk) {
        $batch = [];
        foreach ($chunk as $i => $translation) {
            $batch[(string) ($i + 1)] = [
                'singular' => $translation->getOriginal(),
                'plural' => $translation->getPlural(),
            ];
        }
        try {
            $response = callClaude(buildPluralPrompt($batch, $language, $locale, $nplurals), $model);
        } catch (\RuntimeException $e) {
            fwrite(STDERR, "  ! plural batch {$chunkIndex}: {$e->getMessage()}; skipping (retry next run)\n");
            continue;
        }
        $result = extractJsonObject($response);
        if ($result === null) {
            fwrite(STDERR, "  ! plural batch {$chunkIndex}: could not parse Claude output; skipping\n");
            continue;
        }
        foreach ($chunk as $i => $translation) {
            $key = (string) ($i + 1);
            if (isset($result[$key]) && is_array($result[$key]) && count($result[$key]) >= $nplurals) {
                $forms = array_values(array_map('strval', $result[$key]));
                $translation->setTranslation($forms[0]);
                if ($nplurals > 1) {
                    $translation->setPluralTranslations(array_slice($forms, 1, $nplurals - 1));
                }
                $done++;
            }
        }
        $translations->toPoFile($poFile);
        printf("  plural batch %d: %d entries (%d total)\n", $chunkIndex + 1, count($chunk), $done);
    }
}

printf("Done: %d entries translated in %s\n", $done, basename($poFile));
