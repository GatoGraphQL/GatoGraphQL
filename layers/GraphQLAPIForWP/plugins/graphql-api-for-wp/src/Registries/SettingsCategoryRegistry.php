<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\SettingsCategoryNotExistsException;
use GraphQLAPI\GraphQLAPI\Services\SettingsCategoryResolvers\SettingsCategoryResolverInterface;

class SettingsCategoryRegistry implements SettingsCategoryRegistryInterface
{
    /**
     * @var array<string,SettingsCategoryResolverInterface>
     */
    protected array $settingsCategoryResolvers = [];

    public function addSettingsCategoryResolver(SettingsCategoryResolverInterface $settingsCategoryResolver): void
    {
        foreach ($settingsCategoryResolver->getSettingsCategoriesToResolve() as $settingsCategory) {
            $this->settingsCategoryResolvers[$settingsCategory] = $settingsCategoryResolver;
        }
    }

    /**
     * @throws SettingsCategoryNotExistsException If category does not exist
     */
    public function getSettingsCategoryResolver(string $settingsCategory): SettingsCategoryResolverInterface
    {
        if (!isset($this->settingsCategoryResolvers[$settingsCategory])) {
            throw new SettingsCategoryNotExistsException(sprintf(
                \__('Settings Category \'%s\' does not exist', 'graphql-api'),
                $settingsCategory
            ));
        }
        return $this->settingsCategoryResolvers[$settingsCategory];
    }
}
