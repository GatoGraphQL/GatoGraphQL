<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\UserInterfaceFunctionalityModuleResolver as UpstreamUserInterfaceFunctionalityModuleResolver;

class UserInterfaceFunctionalityModuleResolver extends UpstreamUserInterfaceFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            // self::EXCERPT_AS_DESCRIPTION,
            self::WELCOME_GUIDES,
            self::SCHEMA_CONFIGURATION_ADDITIONAL_DOCUMENTATION
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::EXCERPT_AS_DESCRIPTION
                => true,
            default => parent::isHidden($module),
        };
    }
}
