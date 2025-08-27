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
     * @var array<string,int>
     */
    protected array $logCountBySeverity = [];
    
    private ?LogEntryCounterSettingsManagerInterface $logEntryCounterSettingsManager = null;

    final protected function getLogEntryCounterSettingsManager(): LogEntryCounterSettingsManagerInterface
    {
        return $this->logEntryCounterSettingsManager ??= LogEntryCounterSettingsManagerFacade::getInstance();
    }

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
            $this->warning(sprintf(__('Invalid JSON format for \'%s\'. Ignoring this parameter.', 'gatographql'), $json));
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
        if (!WPCLIHelpers::isWPCLIActive()) {
            echo $message . "\n";
            return;
        }
        call_user_func(WP_CLI::log(...), $message);
    }

    /**
     * Display success message (WP-CLI compatible)
     *
     * @param string $message
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
     *
     * @param string $message
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
     *
     * @param string $message
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
        $severitiesWithLogCountDelta = array_keys(array_filter($logCountBySeverityDelta, fn (int $logCountDelta): bool => $logCountDelta > 0));
        if ($severitiesWithLogCountDelta === []) {
            return;
        }

        $highestLevelSeverity = $this->getLogEntryCounterSettingsManager()->sortSeveritiesByHighestLevel($severitiesWithLogCountDelta)[0];
        $logCountDelta = (string) $logCountBySeverityDelta[$highestLevelSeverity];

        /** @var LogsMenuPage */
        $logsMenuPage = InstanceManagerFacade::getInstance()->getInstance(LogsMenuPage::class);
        $message = sprintf(
            __('There are %d new log entries with severity %s (%s).', 'gatographql'),
            $logCountDelta,
            $highestLevelSeverity,
            admin_url(sprintf(
                'admin.php?page=%s',
                $logsMenuPage->getScreenID()
            ))
        );

        if ($highestLevelSeverity === LoggerSeverity::ERROR || $highestLevelSeverity === LoggerSeverity::WARNING) {
            $this->warning($message);
            return;
        }        
        $this->log($message);
    }
}
