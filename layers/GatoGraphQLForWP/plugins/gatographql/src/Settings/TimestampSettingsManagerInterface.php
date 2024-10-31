<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TimestampSettingsManagerInterface
{
    public function getTimestampValue(string $name): ?int;
    public function storeTimestampValue(string $name, int $timestamp): void;
    /**
     * @param array<string,int> $nameTimestamps Key: name, Value: timestamp
     */
    public function storeTimestampValues(array $nameTimestamps): void;
}
