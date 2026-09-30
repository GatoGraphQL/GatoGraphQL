<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use PoPSchema\Logger\Enums\LoggerSeverity;

interface LogEntryCounterSettingsManagerInterface
{
    /**
     * @param LoggerSeverity|LoggerSeverity[] $severityOrSeverities
     */
    public function getLogCount(LoggerSeverity|array $severityOrSeverities): int;
    /**
     * @param LoggerSeverity[] $severities
     * @return array<string,int> Key: severity value, Value: logCount
     */
    public function getLogCountBySeverity(array $severities): array;
    /**
     * @param LoggerSeverity[] $severities
     * @return LoggerSeverity[]
     */
    public function sortSeveritiesByHighestLevel(array $severities): array;
    public function storeLogCount(LoggerSeverity $severity, int $logCount): void;
    public function increaseLogCount(LoggerSeverity $severity): void;
    /**
     * @param array<string,int> $severityLogCounts Key: severity, Value: logCount
     */
    public function storeLogCounts(array $severityLogCounts): void;
    /**
     * @param LoggerSeverity[] $severities
     */
    public function removeLogCounts(array $severities): void;
}
