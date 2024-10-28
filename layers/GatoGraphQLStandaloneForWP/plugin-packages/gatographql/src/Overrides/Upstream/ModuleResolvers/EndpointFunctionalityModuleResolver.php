<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver as UpstreamEndpointFunctionalityModuleResolver;

class EndpointFunctionalityModuleResolver extends UpstreamEndpointFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::PRIVATE_ENDPOINT,
            self::SINGLE_ENDPOINT
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::PRIVATE_ENDPOINT,
            self::SINGLE_ENDPOINT
                => true,
            default => parent::isHidden($module),
        };
    }
}
