<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQLStandalone\GatoGraphQL\Constants\AdvancedModeOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver as UpstreamPluginGeneralSettingsFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

class PluginGeneralSettingsFunctionalityModuleResolver extends UpstreamPluginGeneralSettingsFunctionalityModuleResolver
{
    /**
     * Setting options
     */
    public final const OPTION_USE_ADVANCED_MODE = 'use-advanced-mode';

    // public function isPredefinedEnabledOrDisabled(string $module): ?bool
    // {
    //     return match ($module) {
    //         // self::GENERAL,
    //         self::SERVER_IP_CONFIGURATION
    //         // self::SCHEMA_EDITING_ACCESS
    //             => false,
    //         default => parent::isPredefinedEnabledOrDisabled($module),
    //     };
    // }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            // self::GENERAL,
            // self::SERVER_IP_CONFIGURATION,
            self::SCHEMA_EDITING_ACCESS
                => true,
            default => parent::isHidden($module),
        };
    }

    /**
     * Allow to select what options to display in the General tab
     *
     * @return string[]
     */
    public function getGeneralTabDisplayableOptionNames(): ?array
    {
        return [
            self::OPTION_ENABLE_LOGS,
            self::OPTION_USE_ADVANCED_MODE,
        ];
    }

    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::GENERAL => [
                // self::OPTION_PRINT_SETTINGS_WITH_TABS => false,
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => false,
                self::OPTION_USE_ADVANCED_MODE => AdvancedModeOptions::DO_NOT_ENABLE_ADVANCED_MODE,
            ],
        ];
        return $defaultValues[$module][$option] ?? parent::getSettingsDefaultValue($module, $option);
    }

    /**
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        if (
            in_array($module, [
                self::SCHEMA_EDITING_ACCESS,
            ])
        ) {
            return [];
        }

        $moduleSettings = parent::getSettings($module);
        if ($module === self::GENERAL) {
            $generalTabDisplayableOptionNames = $this->getGeneralTabDisplayableOptionNames();

            $option = self::OPTION_USE_ADVANCED_MODE;
            if (
                $this->enableGeneralTabAdvancedModeOption()
                && ($generalTabDisplayableOptionNames === null || in_array($option, $generalTabDisplayableOptionNames))
            ) {
                $possibleValues = [
                    AdvancedModeOptions::DO_NOT_ENABLE_ADVANCED_MODE => \__('Do not enable the Advanced Mode', 'gatographql'),
                    AdvancedModeOptions::ENABLE_ADVANCED_MODE => \__('Enable the Advanced Mode', 'gatographql'),
                    AdvancedModeOptions::ENABLE_ADVANCED_MODE_AND_DISABLE_AUTOMATIC_CONFIG_UPDATES => $this->getGeneralTabAdvancedModeOptionName(),
                ];
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Enable the Advanced Mode?', 'gatographql'),
                    Properties::DESCRIPTION => $this->getGeneralTabAdvancedModeOptionDescription(),
                    Properties::TYPE => Properties::TYPE_STRING,
                    Properties::POSSIBLE_VALUES => $possibleValues,
                ];
            }
        }

        return $moduleSettings;
    }

    protected function enableGeneralTabAdvancedModeOption(): bool
    {
        return false;
    }

    protected function getGeneralTabAdvancedModeOptionName(): string
    {
        return \__('Enable the Advanced Mode and Disable Automatic Config Updates', 'gatographql');
    }
    
    protected function getGeneralTabAdvancedModeOptionDescription(): string
    {
        return \__('Adapt the behavior of the plugin using the Advanced Mode', 'gatographql');
    }
}
