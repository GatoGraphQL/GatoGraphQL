<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;

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

    public function getLogCount(string $severity): int
    {
        /** @var array<string,int> */
        $logCounts = get_option($this->namespaceOption(Options::LOG_COUNTS), []);
        return $logCounts[$severity] ?? 0;
    }

    protected function namespaceOption(string $option): string
    {
        return $this->getOptionNamespacer()->namespaceOption($option);
    }

    public function storeLogCount(string $severity, int $logCount): void
    {
        $this->storeLogCounts([$severity => $logCount]);
    }

    /**
     * @param array<string,string> $severityLogCounts Key: severity, Value: logCount
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
            $severityLogCounts
        );
        update_option($option, $logCounts);
    }

    /**
     * @param string[] $severities
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
            unset($logCounts[$severity]);
        }

        /**
         * If there were no other keys, can safely delete the option
         */
        if ($logCounts === []) {
            delete_option($option);
            return;
        }

        update_option($option, $logCounts);
    }
}
