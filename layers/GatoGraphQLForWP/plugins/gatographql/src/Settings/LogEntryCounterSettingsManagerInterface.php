<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface LogEntryCounterSettingsManagerInterface
{
    public function getLogCount(string $severity, ?int $defaultValue = 0): ?int;
    public function storeLogCount(string $severity, int $timestamp): void;
    /**
     * @param array<string,int> $severityLogCounts Key: severity, Value: logCount
     */
    public function storeLogCounts(array $severityLogCounts): void;
    /**
     * @param string[] $severities
     */
    public function removeLogCounts(array $severities): void;
}
