<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\PluginManagementFunctionalityModuleResolver as UpstreamPluginManagementFunctionalityModuleResolver;

class PluginManagementFunctionalityModuleResolver extends UpstreamPluginManagementFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            // self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            // self::ACTIVATE_EXTENSIONS,
            self::RESET_SETTINGS
                => true,
            default => parent::isHidden($module),
        };
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ACTIVATE_EXTENSIONS => \__('Activate', 'gatographql'),
            default => parent::getName($module),
        };
    }

    protected function getActivateExtensionLicensesTitle(): string
    {
        return \__('Activate licenses', 'gatographql');
    }
}
