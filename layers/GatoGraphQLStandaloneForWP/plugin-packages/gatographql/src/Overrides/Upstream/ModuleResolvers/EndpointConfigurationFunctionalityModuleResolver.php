<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver as UpstreamEndpointConfigurationFunctionalityModuleResolver;

class EndpointConfigurationFunctionalityModuleResolver extends UpstreamEndpointConfigurationFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::API_HIERARCHY
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::API_HIERARCHY
                => true,
            default => parent::isHidden($module),
        };
    }
}
