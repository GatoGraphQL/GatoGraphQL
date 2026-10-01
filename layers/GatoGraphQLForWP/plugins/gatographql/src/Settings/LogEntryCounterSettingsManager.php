<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;
use PoPSchema\Logger\Enums\LoggerSeverity;

use function delete_option;
use function get_option;
use function update_option;

class LogEntryCounterSettingsManager implements LogEntryCounterSettingsManagerInterface
{
    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        return $this->optionNamespacer ??= OptionNamespacerFacade::getInstance();
    }

    /**
     * @param LoggerSeverity|LoggerSeverity[] $severityOrSeverities
     */
    public function getLogCount(LoggerSeverity|array $severityOrSeverities): int
    {
        $severities = is_array($severityOrSeverities) ? $severityOrSeverities : [$severityOrSeverities];
        return array_sum($this->getLogCountBySeverity($severities));
    }

    /**
     * @param LoggerSeverity[] $severities
     * @return array<string,int> Key: severity value, Value: logCount
     */
    public function getLogCountBySeverity(array $severities): array
    {
        /** @var array<string,int> */
        $logCounts = get_option($this->namespaceOption(Options::LOG_COUNTS), []);

        $logCountsBySeverity = [];
        foreach ($severities as $severity) {
            $logCountsBySeverity[$severity->value] = $logCounts[strtolower($severity->value)] ?? 0;
        }
        return $logCountsBySeverity;
    }

    /**
     * @param LoggerSeverity[] $severities
     * @return LoggerSeverity[]
     */
    public function sortSeveritiesByHighestLevel(array $severities): array
    {
        return array_values(array_filter(
            LoggerSeverity::cases(),
            fn (LoggerSeverity $severity): bool => in_array($severity, $severities, true),
        ));
    }

    protected function namespaceOption(string $option): string
    {
        return $this->getOptionNamespacer()->namespaceOption($option);
    }

    public function storeLogCount(LoggerSeverity $severity, int $logCount): void
    {
        $this->storeLogCounts([$severity->value => $logCount]);
    }

    public function increaseLogCount(LoggerSeverity $severity): void
    {
        $this->storeLogCount($severity, $this->getLogCount($severity) + 1);
    }

    /**
     * @param array<string,int> $severityLogCounts Key: severity, Value: logCount
     */
    public function storeLogCounts(array $severityLogCounts): void
    {
        $option = $this->namespaceOption(Options::LOG_COUNTS);

        /**
         * Get the current logCounts from the DB
         * @var array<string,string>
         */
        $logCounts = get_option($option, []);

        /**
         * Override with the provided values
         */
        $logCounts = array_merge(
            $logCounts,
            array_change_key_case($severityLogCounts, CASE_LOWER)
        );
        update_option($option, $logCounts, false);
    }

    /**
     * @param LoggerSeverity[] $severities
     */
    public function removeLogCounts(array $severities): void
    {
        $option = $this->namespaceOption(Options::LOG_COUNTS);

        /**
         * Remove only the provided keys
         *
         * @var array<string,string>
         */
        $logCounts = get_option($option, []);
        foreach ($severities as $severity) {
            unset($logCounts[strtolower($severity->value)]);
        }

        /**
         * If there were no other keys, can safely delete the option
         */
        if ($logCounts === []) {
            delete_option($option);
            return;
        }

        update_option($option, $logCounts, false);
    }
}
