<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TimestampSettingsManagerInterface
{
    public function getTimestamp(string $name, ?string $defaultValue = null): ?string;
    public function storeTimestamp(string $name, string $timestamp): void;
    /**
     * @param array<string,string> $nameTimestamps Key: name, Value: timestamp
     */
    public function storeTimestamps(array $nameTimestamps): void;
}
