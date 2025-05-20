<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface LogEntryCounterSettingsManagerInterface
{
    /**
     * @param string|string[] $severityOrSeverities
     */
    public function getLogCount(string|array $severityOrSeverities): int;
    public function storeLogCount(string $severity, int $logCount): void;
    public function increaseLogCount(string $severity): void;
    /**
     * @param array<string,int> $severityLogCounts Key: severity, Value: logCount
     */
    public function storeLogCounts(array $severityLogCounts): void;
    /**
     * @param string[] $severities
     */
    public function removeLogCounts(array $severities): void;
}
