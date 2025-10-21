<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\WPCLI;

use GatoGraphQL\GatoGraphQL\Facades\LogEntryCounterSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\LogsMenuPage;
use GatoGraphQL\GatoGraphQL\Settings\LogEntryCounterSettingsManagerInterface;
use GatoGraphQL\GatoGraphQL\StaticHelpers\WPCLIHelpers;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoPSchema\Logger\Constants\LoggerSeverity;
use WP_CLI;

use function __;

abstract class AbstractWPCLICommand
{
    /**
     * Color name constants
     */
    protected const LOG_COLOR_BLUE = 'blue';
    protected const LOG_COLOR_RED = 'red';
    protected const LOG_COLOR_GREEN = 'green';
    protected const LOG_COLOR_YELLOW = 'yellow';
    protected const LOG_COLOR_CYAN = 'cyan';
    protected const LOG_COLOR_MAGENTA = 'magenta';
    protected const LOG_COLOR_WHITE = 'white';

    /**
     * @var array<string,int>
     */
    protected array $logCountBySeverity = [];

    private ?LogEntryCounterSettingsManagerInterface $logEntryCounterSettingsManager = null;

    final protected function getLogEntryCounterSettingsManager(): LogEntryCounterSettingsManagerInterface
    {
        return $this->logEntryCounterSettingsManager ??= LogEntryCounterSettingsManagerFacade::getInstance();
    }

    /**
     * Parse IDs separated by commas or spaces into an array of integers
     *
     * @return int[]
     */
    protected function parseIds(string $idsString): array
    {
        if (empty($idsString)) {
            return [];
        }

        // Split by comma first, then by space for each comma-separated part
        $commaParts = explode(',', $idsString);
        $ids = [];

        foreach ($commaParts as $part) {
            $spaceParts = explode(' ', trim($part));
            foreach ($spaceParts as $id) {
                $id = trim($id);
                if (!empty($id)) {
                    $ids[] = $id;
                }
            }
        }

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
     */
    protected function parseBool(?string $value): ?bool
    {
        if ($value === null) {
            return null;
        }
        return in_array(strtolower($value), ['true', '1', 'yes', 'on'], true);
    }

    /**
     * Parse language providers JSON string
     *
     * @return array<string,string>|null
     */
    protected function parseJSON(?string $json): ?array
    {
        if (empty($json)) {
            return null;
        }

        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->warning(sprintf(__('Invalid JSON format for \'%s\'. Ignoring this parameter.', 'gatographql'), $json));
            return null;
        }

        return $decoded;
    }

    /**
     * Colorize a message for WP-CLI output
     */
    protected function colorizeMessage(string $message, string $color): string
    {
        if (!WPCLIHelpers::isWPCLIActive()) {
            return $message;
        }
        $colorCode = match ($color) {
            self::LOG_COLOR_BLUE => '%B',
            self::LOG_COLOR_RED => '%R',
            self::LOG_COLOR_GREEN => '%G',
            self::LOG_COLOR_YELLOW => '%Y',
            self::LOG_COLOR_CYAN => '%C',
            self::LOG_COLOR_MAGENTA => '%M',
            self::LOG_COLOR_WHITE => '%W',
            default => '%n',
        };
        return WP_CLI::colorize($colorCode . $message . '%n');
    }

    /**
     * Log a message (WP-CLI compatible)
     */
    protected function log(string $message): void
    {
        if (!WPCLIHelpers::isWPCLIActive()) {
            echo $message . "\n";
            return;
        }
        call_user_func(WP_CLI::log(...), $message);
    }

    /**
     * Display success message (WP-CLI compatible)
     */
    protected function success(string $message): void
    {
        if (!WPCLIHelpers::isWPCLIActive()) {
            echo "SUCCESS: " . $message . "\n";
            return;
        }
        call_user_func(WP_CLI::success(...), $message);
    }

    /**
     * Display warning message (WP-CLI compatible)
     */
    protected function warning(string $message): void
    {
        if (!WPCLIHelpers::isWPCLIActive()) {
            echo "WARNING: " . $message . "\n";
            return;
        }
        call_user_func(WP_CLI::warning(...), $message);
    }

    /**
     * Display error message and exit (WP-CLI compatible)
     */
    protected function error(string $message): void
    {
        if (!WPCLIHelpers::isWPCLIActive()) {
            echo "ERROR: " . $message . "\n";
            exit(1);
        }
        call_user_func(WP_CLI::error(...), $message);
        exit(1);
    }

    protected function storeLogsBySeverity(): void
    {
        $this->logCountBySeverity = $this->getLogEntryCounterSettingsManager()->getLogCountBySeverity(LoggerSeverity::ALL);
    }

    /**
     * Compute the number of Log messages added during the execution of the
     * WP-CLI command, for all severities.
     *
     * @return array<string,int> Number of new Log messages for each severity
     */
    protected function computeLogsBySeverityDelta(): array
    {
        $logCountBySeverity = $this->getLogEntryCounterSettingsManager()->getLogCountBySeverity(LoggerSeverity::ALL);
        $logCountBySeverityDelta = [];
        foreach (LoggerSeverity::ALL as $severity) {
            $logCountBySeverityDelta[$severity] = $logCountBySeverity[$severity] - ($this->logCountBySeverity[$severity] ?? 0);
        }
        return $logCountBySeverityDelta;
    }

    /**
     * @param array<string,int> $logCountBySeverityDelta
     */
    protected function maybePrintLogsMessage(array $logCountBySeverityDelta): void
    {
        $severitiesWithLogCountDelta = $this->getSeveritiesWithLogCountDelta($logCountBySeverityDelta);
        if ($severitiesWithLogCountDelta === []) {
            return;
        }

        $highestLevelSeverity = $this->getLogEntryCounterSettingsManager()->sortSeveritiesByHighestLevel($severitiesWithLogCountDelta)[0];
        $logCountDelta = (string) $logCountBySeverityDelta[$highestLevelSeverity];

        $message = sprintf(
            $logCountDelta > 1
                ? __('There are %d new log entries with severity %s', 'gatographql')
                : __('There is %d new log entry with severity %s', 'gatographql'),
            $logCountDelta,
            $highestLevelSeverity
        );

        if ($highestLevelSeverity === LoggerSeverity::ERROR) {
            $message = $this->colorizeMessage($message, self::LOG_COLOR_RED);
        } elseif ($highestLevelSeverity === LoggerSeverity::WARNING) {
            $message = $this->colorizeMessage($message, self::LOG_COLOR_YELLOW);
        } elseif ($highestLevelSeverity === LoggerSeverity::INFO) {
            $message = $this->colorizeMessage($message, self::LOG_COLOR_BLUE);
        } else {
            $message = $this->colorizeMessage($message, self::LOG_COLOR_MAGENTA);
        }

        /** @var LogsMenuPage */
        $logsMenuPage = InstanceManagerFacade::getInstance()->getInstance(LogsMenuPage::class);
        $logsMenuPageURL = admin_url(sprintf(
            'admin.php?page=%s',
            $logsMenuPage->getScreenID()
        ));
        $message .= sprintf(
            __(' (%s)', 'gatographql'),
            $this->colorizeMessage($logsMenuPageURL, self::LOG_COLOR_CYAN)
        );

        if ($highestLevelSeverity === LoggerSeverity::ERROR || $highestLevelSeverity === LoggerSeverity::WARNING) {
            $this->warning($message);
            return;
        }
        $this->log($message);
    }

    /**
     * @param array<string,int> $logCountBySeverityDelta
     * @return string[]
     */
    protected function getSeveritiesWithLogCountDelta(array $logCountBySeverityDelta): array
    {
        return array_keys(array_filter($logCountBySeverityDelta, fn (int $logCountDelta): bool => $logCountDelta > 0));
    }
}
