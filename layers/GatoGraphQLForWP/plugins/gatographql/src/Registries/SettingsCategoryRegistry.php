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
    /**
     * @var array<string,SettingsCategoryResolverInterface>|null
     */
    protected ?array $settingsCategorySettingsCategoryResolversByPriority = null;

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
                \__('Settings Category \'%s\' does not exist', 'gatographql'),
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
        if ($this->settingsCategorySettingsCategoryResolversByPriority === null) {
            $this->settingsCategorySettingsCategoryResolversByPriority = [];
            $settingsCategorySettingsCategoryResolversByPriority = [];
            foreach ($this->settingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                $settingsCategorySettingsCategoryResolversByPriority[$settingsCategoryResolver->getPriority($settingsCategory)][$settingsCategory] = $settingsCategoryResolver;
            }
            uksort($settingsCategorySettingsCategoryResolversByPriority, function (int $a, int $b): int {
                return $b <=> $a;
            });
            foreach ($settingsCategorySettingsCategoryResolversByPriority as $priority => $settingsCategorySettingsCategoryResolvers) {
                foreach ($settingsCategorySettingsCategoryResolvers as $settingsCategory => $settingsCategoryResolver) {
                    $this->settingsCategorySettingsCategoryResolversByPriority[$settingsCategory] = $settingsCategoryResolver;
                }                
            }
        }
        return $this->settingsCategorySettingsCategoryResolversByPriority;
    }
}
