<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver as UpstreamClientFunctionalityModuleResolver;

class ClientFunctionalityModuleResolver extends UpstreamClientFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GRAPHIQL_FOR_SINGLE_ENDPOINT,
            self::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT
                => true,
            default => parent::isHidden($module),
        };
    }
}
