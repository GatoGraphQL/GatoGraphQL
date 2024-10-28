<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\DeprecatedClientFunctionalityModuleResolver as UpstreamDeprecatedClientFunctionalityModuleResolver;

class DeprecatedClientFunctionalityModuleResolver extends UpstreamDeprecatedClientFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GRAPHIQL_EXPLORER
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GRAPHIQL_EXPLORER
                => true,
            default => parent::isHidden($module),
        };
    }
}
