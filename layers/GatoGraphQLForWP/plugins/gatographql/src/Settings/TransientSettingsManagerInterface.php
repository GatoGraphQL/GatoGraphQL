<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface TransientSettingsManagerInterface
{
    public function getTransient(string $name, mixed $defaultValue = null): mixed;
    public function storeTransient(string $name, mixed $transient): void;
    /**
     * @param array<string,mixed> $nameTransients Key: name, Value: transient data
     */
    public function storeTransients(array $nameTransients): void;
    /**
     * @param string[] $names
     */
    public function removeTransients(array $names): void;
}
