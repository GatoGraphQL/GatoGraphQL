<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface JSONDataOptionSettingsManagerInterface
{
    public function getJSONData(string $name, ?array $defaultValue = null): ?array;
    public function storeJSONData(string $name, array $data): void;
    /**
     * @param array<string,array<mixed>> $nameData Key: name, Value: data
     */
    public function storeJSONDataMultiple(array $nameData): void;
    /**
     * @param string[] $names
     */
    public function removeJSONData(array $names): void;
}
