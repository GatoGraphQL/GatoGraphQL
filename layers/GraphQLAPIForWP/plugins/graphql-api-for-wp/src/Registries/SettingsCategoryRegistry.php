<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Exception\SettingsCategoryNotExistsException;
use GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers\SettingsCategoryResolverInterface;

class SettingsCategoryRegistry implements SettingsCategoryRegistryInterface
{
    /**
     * @var SettingsCategoryResolverInterface[]
     */
    protected array $settingsCategoryResolvers = [];
    /**
     * @var array<string,SettingsCategoryResolverInterface>
     */
    protected array $settingsCategorySettingsCategoryResolvers = [];

    public function addSettingsCategoryResolver(SettingsCategoryResolverInterface $settingsCategoryResolver): void
    {
        $this->settingsCategoryResolvers[] = $settingsCategoryResolver;
        foreach ($settingsCategoryResolver->getSettingsCategoriesToResolve() as $settingsCategory) {
            $this->settingsCategorySettingsCategoryResolvers[$settingsCategory] = $settingsCategoryResolver;
        }
    }

    /**
     * @throws SettingsCategoryNotExistsException If category does not exist
     */
    public function getSettingsCategoryResolver(string $settingsCategory): SettingsCategoryResolverInterface
    {
        if (!isset($this->settingsCategorySettingsCategoryResolvers[$settingsCategory])) {
            throw new SettingsCategoryNotExistsException(sprintf(
                \__('Settings Category \'%s\' does not exist', 'graphql-api'),
                $settingsCategory
            ));
        }
        return $this->settingsCategorySettingsCategoryResolvers[$settingsCategory];
    }

    /**
     * @return SettingsCategoryResolverInterface[]
     */
    public function getSettingsCategoryResolvers(): array
    {
        return $this->settingsCategoryResolvers;
    }

    /**
     * @return array<string,SettingsCategoryResolverInterface>
     */
    public function getSettingsCategorySettingsCategoryResolvers(): array
    {
        return $this->settingsCategorySettingsCategoryResolvers;
    }
}
