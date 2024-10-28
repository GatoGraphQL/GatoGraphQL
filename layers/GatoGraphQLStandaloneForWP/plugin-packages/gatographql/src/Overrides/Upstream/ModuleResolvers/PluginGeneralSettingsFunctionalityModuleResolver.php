<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver as UpstreamPluginGeneralSettingsFunctionalityModuleResolver;

class PluginGeneralSettingsFunctionalityModuleResolver extends UpstreamPluginGeneralSettingsFunctionalityModuleResolver
{
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
        ];
    }

    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::GENERAL => [
                self::OPTION_PRINT_SETTINGS_WITH_TABS => false,
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => false,
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

        return parent::getSettings($module);
    }
}
