<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton;

use GatoGraphQLStandalone\GatoGraphQL\Constants\AdvancedModeOptions;
use GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\PluginInitializationConfiguration;

abstract class AbstractStandalonePluginInitializationConfiguration extends PluginInitializationConfiguration
{
    /**
     * Define the values for certain environment constants from the plugin settings
     *
     * @return array<array<string,mixed>>
     */
    protected function getEnvironmentConstantsFromSettingsMapping(): array
    {
        return array_merge(
            parent::getEnvironmentConstantsFromSettingsMapping(),
            [
                // Use the "Advanced Mode"
                [
                    'class' => \GatoGraphQLStandalone\GatoGraphQL\Module::class,
                    'envVariable' => \GatoGraphQLStandalone\GatoGraphQL\Environment::ENABLE_ADVANCED_MODE,
                    'module' => PluginGeneralSettingsFunctionalityModuleResolver::GENERAL,
                    'option' => PluginGeneralSettingsFunctionalityModuleResolver::OPTION_USE_ADVANCED_MODE,
                    // Depending on `useBooleanForAdvancedModeOption`, the value can be either a string or a boolean
                    'callback' => fn ($value) => (is_string($value) && $value !== AdvancedModeOptions::DO_NOT_ENABLE_ADVANCED_MODE) || (is_bool($value) && $value),
                ],
                [
                    'class' => \GatoGraphQLStandalone\GatoGraphQL\Module::class,
                    'envVariable' => \GatoGraphQLStandalone\GatoGraphQL\Environment::DISABLE_AUTOMATIC_CONFIG_UPDATES,
                    'module' => PluginGeneralSettingsFunctionalityModuleResolver::GENERAL,
                    'option' => PluginGeneralSettingsFunctionalityModuleResolver::OPTION_USE_ADVANCED_MODE,
                    // Depending on `useBooleanForAdvancedModeOption`, the value can be either a string or a boolean
                    'callback' => fn ($value) => (is_string($value) && $value === AdvancedModeOptions::ENABLE_ADVANCED_MODE_AND_DISABLE_AUTOMATIC_CONFIG_UPDATES),
                ],
            ]
        );
    }
}
