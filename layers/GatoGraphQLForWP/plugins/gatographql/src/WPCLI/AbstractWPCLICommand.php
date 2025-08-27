<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\WPCLI;

use WP_CLI;

use function __;

abstract class AbstractWPCLICommand
{
    /**
     * Parse comma-separated IDs into an array of integers
     *
     * @param string $idsString
     * @return int[]
     */
    protected function parseIds(string $idsString): array
    {
        if (empty($idsString)) {
            return [];
        }

        $ids = array_map('trim', explode(',', $idsString));
        $validIds = [];

        foreach ($ids as $id) {
            $id = (int) $id;
            if ($id > 0) {
                $validIds[] = $id;
            }
        }

        return $validIds;
    }

    /**
     * Parse boolean string to actual boolean
     *
     * @param string $value
     * @return bool
     */
    protected function parseBool(string $value): bool
    {
        return in_array(strtolower($value), ['true', '1', 'yes', 'on'], true);
    }

    /**
     * Parse language providers JSON string
     *
     * @param string|null $json
     * @return array<string,string>|null
     */
    protected function parseJSON(?string $json): ?array
    {
        if (empty($json)) {
            return null;
        }

        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->warning(__('Invalid JSON format for language-providers. Ignoring this parameter.', 'gatographql-ai-translations-for-polylang'));
            return null;
        }

        return $decoded;
    }

    /**
     * Log a message (WP-CLI compatible)
     *
     * @param string $message
     */
    protected function log(string $message): void
    {
        if (!$this->isWPCLIActive()) {
            echo $message . "\n";
            return;
        }
        call_user_func(WP_CLI::log(...), $message);
    }

    protected function isWPCLIActive(): bool
    {
        return defined('WP_CLI') && constant('WP_CLI') && class_exists('WP_CLI');
    }

    /**
     * Display success message (WP-CLI compatible)
     *
     * @param string $message
     */
    protected function success(string $message): void
    {
        if (!$this->isWPCLIActive()) {
            echo "SUCCESS: " . $message . "\n";
            return;
        }
        call_user_func(WP_CLI::success(...), $message);
    }

    /**
     * Display warning message (WP-CLI compatible)
     *
     * @param string $message
     */
    protected function warning(string $message): void
    {
        if (!$this->isWPCLIActive()) {
            echo "WARNING: " . $message . "\n";
            return;
        }
        call_user_func(WP_CLI::warning(...), $message);
    }

    /**
     * Display error message and exit (WP-CLI compatible)
     *
     * @param string $message
     */
    protected function error(string $message): void
    {
        if (!$this->isWPCLIActive()) {
            echo "ERROR: " . $message . "\n";
            exit(1);
        }
        call_user_func(WP_CLI::error(...), $message);
        exit(1);
    }
}
