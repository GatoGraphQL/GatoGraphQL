<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Exception\SettingsCategoryNotExistsException;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolverInterface;

interface SettingsCategoryRegistryInterface
{
    public function addSettingsCategoryResolver(SettingsCategoryResolverInterface $settingsCategoryResolver): void;
    /**
     * @throws SettingsCategoryNotExistsException If category does not exist
     */
    public function getSettingsCategoryResolver(string $settingsCategory): SettingsCategoryResolverInterface;
    /**
     * @return SettingsCategoryResolverInterface[]
     */
    public function getSettingsCategoryResolvers(): array;
    /**
     * @return array<string,SettingsCategoryResolverInterface>
     */
    public function getSettingsCategorySettingsCategoryResolvers(): array;
}
