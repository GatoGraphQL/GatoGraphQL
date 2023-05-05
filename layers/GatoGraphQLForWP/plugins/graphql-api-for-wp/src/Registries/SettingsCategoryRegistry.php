<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Registries;

use GatoGraphQL\GatoGraphQL\Exception\SettingsCategoryNotExistsException;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolverInterface;

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
                \__('Settings Category \'%s\' does not exist', 'gato-graphql'),
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
