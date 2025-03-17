<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TransientSettingsManagerInterface
{
    public function getTransient(string $name, ?string $defaultValue = null): ?string;
    public function storeTransient(string $name, string $timestamp): void;
    /**
     * @param array<string,string> $nameTransients Key: name, Value: timestamp
     */
    public function storeTransients(array $nameTransients): void;
    /**
     * @param string[] $names
     */
    public function removeTransients(array $names): void;
}
