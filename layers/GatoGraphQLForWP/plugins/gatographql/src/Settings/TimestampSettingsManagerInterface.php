<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TimestampSettingsManagerInterface
{
    public function getTimestamp(string $name, ?string $defaultValue = null): ?string;
    /**
     * @param string|null $timestamp If null, use the current time
     */
    public function storeTimestamp(string $name, ?string $timestamp = null): void;
    /**
     * @param array<string,string> $nameTimestamps Key: name, Value: timestamp
     */
    public function storeTimestamps(array $nameTimestamps): void;
    /**
     * @param string[] $names
     */
    public function removeTimestamps(array $names): void;
}
