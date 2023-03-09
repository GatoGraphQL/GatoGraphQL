<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\SettingsCategoryNotExistsException;
use GraphQLAPI\GraphQLAPI\Services\SettingsCategoryResolvers\SettingsCategoryResolverInterface;

interface SettingsCategoryRegistryInterface
{
    public function addSettingsCategoryResolver(SettingsCategoryResolverInterface $settingsCategoryResolver): void;
    /**
     * @throws SettingsCategoryNotExistsException If category does not exist
     */
    public function getSettingsCategoryResolver(string $settingsCategory): SettingsCategoryResolverInterface;
}
