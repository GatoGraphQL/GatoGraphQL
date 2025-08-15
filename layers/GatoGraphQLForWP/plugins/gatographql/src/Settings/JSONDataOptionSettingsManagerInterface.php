<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

interface JSONDataOptionSettingsManagerInterface
{
    /**
     * @param mixed[]|null $defaultValue
     * @return mixed[]|null
     */
    public function getJSONData(string $name, ?array $defaultValue = null): ?array;
    
    /**
     * @param mixed[] $data
     */
    public function storeJSONData(string $name, array $data): void;
    
    /**
     * @param array<string,mixed[]> $nameData Key: name, Value: data
     */
    public function storeJSONDataMultiple(array $nameData): void;
    
    /**
     * @param string[] $names
     */
    public function removeJSONData(array $names): void;
}
