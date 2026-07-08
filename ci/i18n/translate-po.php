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
        'pl_PL' => 'Polish (Poland)',
        'ko_KR' => 'Korean',
        'vi'    => 'Vietnamese',
        'th'    => 'Thai',
        'id_ID' => 'Indonesian',
        'tr_TR' => 'Turkish',
        'sv_SE' => 'Swedish (Sweden)',
        'el'    => 'Greek',
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
        - Return STRICTLY valid JSON (RFC 8259): escape every double quote inside a value as \\", and write newlines as \\n — never a raw line break inside a value.
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

        Rules: return STRICTLY valid JSON (escape every double quote inside a value as \\", newlines as \\n); preserve every placeholder verbatim (%s, %d, %1\$s, ...), HTML tags/entities, the contents of <code>…</code>, and leading/trailing whitespace.

        {$input}
        PROMPT;
}

/**
 * Last-resort prompt for a single entry that keeps breaking JSON parsing
 * (typically because its translation contains double quotes the model fails to
 * escape). Asks for the raw translated text only — no JSON to mis-escape.
 */
function buildRawPrompt(string $original, string $language, string $locale): string
{
    return <<<PROMPT
        You are localizing the "Gato GraphQL" WordPress plugin (a GraphQL server for WordPress) into {$language} (locale {$locale}).

        Translate the text delimited by the markers below from English to {$language}.

        Output ONLY the translated text itself — no surrounding quotes, no JSON, no markdown, no code fences, no commentary.

        Rules:
        - Preserve every placeholder verbatim: %s, %d, %1\$s, %2\$s, etc.
        - Preserve HTML tags and entities and any Markdown, including any double quotes that appear in the text.
        - Do NOT translate the contents of <code>…</code>, code identifiers, GraphQL keywords, field/type names, or URLs.
        - Preserve leading/trailing whitespace.

        <text>{$original}</text>
        PROMPT;
}

/**
 * Translate one chunk of singular entries. A chunk whose model output won't
 * parse (or that comes back missing entries) is BISECTED to isolate the
 * offending entry, which is then retried on its own and finally via a raw
 * (non-JSON) prompt — so one poison entry can no longer silently sink a batch.
 * Returns the number of entries newly translated; entries that still fail are
 * left untranslated (and reported by the caller).
 *
 * @param \Gettext\Translation[] $chunk
 */
function translateChunk(array $chunk, string $language, string $locale, string $model, callable $persist): int
{
    if ($chunk === []) {
        return 0;
    }

    $batch = [];
    foreach (array_values($chunk) as $i => $translation) {
        $batch[(string) ($i + 1)] = $translation->getOriginal();
    }

    $result = null;
    try {
        $result = extractJsonObject(callClaude(buildPrompt($batch, $language, $locale), $model));
    } catch (\RuntimeException $e) {
        fwrite(STDERR, "  ! claude error: {$e->getMessage()}\n");
    }

    $done = 0;
    /** @var \Gettext\Translation[] $failed */
    $failed = [];
    foreach (array_values($chunk) as $i => $translation) {
        $key = (string) ($i + 1);
        if (is_array($result) && isset($result[$key]) && is_string($result[$key]) && $result[$key] !== '') {
            $translation->setTranslation($result[$key]);
            $done++;
        } else {
            $failed[] = $translation;
        }
    }
    if ($done > 0) {
        $persist();
    }
    if ($failed === []) {
        return $done;
    }

    // More than one entry left: split to isolate the offender.
    if (count($failed) > 1) {
        $half = intdiv(count($failed), 2);
        $done += translateChunk(array_slice($failed, 0, $half), $language, $locale, $model, $persist);
        $done += translateChunk(array_slice($failed, $half), $language, $locale, $model, $persist);
        return $done;
    }

    // A single entry still failing through JSON: fall back to a raw-text prompt.
    $translation = $failed[0];
    $raw = translateSingleRaw($translation->getOriginal(), $language, $locale, $model);
    if ($raw !== null) {
        $translation->setTranslation($raw);
        $persist();
        return $done + 1;
    }
    fwrite(STDERR, "  ! left untranslated: \"" . shortenForLog($translation->getOriginal()) . "\"\n");
    return $done;
}

/**
 * Translate a single string via the raw (non-JSON) prompt, with retries.
 * Returns the translated text, or null if it could not be obtained.
 */
function translateSingleRaw(string $original, string $language, string $locale, string $model, int $maxAttempts = 2): ?string
{
    for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
        try {
            $response = callClaude(buildRawPrompt($original, $language, $locale), $model);
        } catch (\RuntimeException $e) {
            return null;
        }
        // strip accidental code fences, then surrounding whitespace
        $text = (string) preg_replace('/^```[a-z]*\s*|\s*```$/m', '', trim($response));
        $text = trim($text);
        if ($text !== '') {
            return $text;
        }
    }
    return null;
}

function shortenForLog(string $text): string
{
    $text = str_replace("\n", ' ', $text);
    return mb_strlen($text) > 60 ? mb_substr($text, 0, 57) . '...' : $text;
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

// persist incrementally so a long run can be resumed
$persist = static function () use ($translations, $poFile): void {
    $translations->toPoFile($poFile);
};

$done = 0;
foreach (array_chunk($pending, $batchSize) as $chunkIndex => $chunk) {
    $done += translateChunk($chunk, $language, $locale, $model, $persist);
    printf("  batch %d done (%d/%d translated so far)\n", $chunkIndex + 1, $done, count($pending));
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
        $result = null;
        for ($attempt = 1; $attempt <= 2 && $result === null; $attempt++) {
            try {
                $response = callClaude(buildPluralPrompt($batch, $language, $locale, $nplurals), $model);
            } catch (\RuntimeException $e) {
                fwrite(STDERR, "  ! plural batch {$chunkIndex}: {$e->getMessage()}\n");
                break;
            }
            $result = extractJsonObject($response);
        }
        if ($result === null) {
            fwrite(STDERR, "  ! plural batch {$chunkIndex}: could not parse Claude output; left for next run\n");
            continue;
        }
        foreach ($chunk as $i => $translation) {
            $key = (string) ($i + 1);
            if (!isset($result[$key]) || !is_array($result[$key])) {
                continue;
            }
            $forms = array_slice(array_values($result[$key]), 0, $nplurals);
            if (count($forms) < $nplurals) {
                continue;
            }
            $allStrings = true;
            foreach ($forms as $form) {
                if (!is_string($form) || $form === '') {
                    $allStrings = false;
                    break;
                }
            }
            if (!$allStrings) {
                continue;
            }
            $translation->setTranslation($forms[0]);
            if ($nplurals > 1) {
                $translation->setPluralTranslations(array_slice($forms, 1, $nplurals - 1));
            }
            $done++;
        }
        $translations->toPoFile($poFile);
        printf("  plural batch %d: %d entries (%d total)\n", $chunkIndex + 1, count($chunk), $done);
    }
}

printf("Done: %d entries translated in %s\n", $done, basename($poFile));

// Surface anything still untranslated so it can't slip through silently.
$remaining = 0;
foreach ($pending as $translation) {
    if (!$translation->hasTranslation()) {
        $remaining++;
    }
}
if ($limit === 0) {
    foreach ($pendingPlural as $translation) {
        if (!$translation->hasTranslation()) {
            $remaining++;
        }
    }
}
if ($remaining > 0) {
    fwrite(STDERR, sprintf(
        "WARNING: %d entr%s in %s still untranslated after all retries; re-run to retry.\n",
        $remaining,
        $remaining === 1 ? 'y' : 'ies',
        basename($poFile)
    ));
    exit(3);
}
