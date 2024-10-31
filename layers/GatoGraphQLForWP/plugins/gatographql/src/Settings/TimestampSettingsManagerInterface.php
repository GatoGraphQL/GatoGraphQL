<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TimestampSettingsManagerInterface
{
    public function getTimestamp(string $name): ?int;
    public function storeTimestamp(string $name, int $timestamp): void;
    /**
     * @param array<string,int> $nameTimestamps Key: name, Value: timestamp
     */
    public function storeTimestamps(array $nameTimestamps): void;
}
