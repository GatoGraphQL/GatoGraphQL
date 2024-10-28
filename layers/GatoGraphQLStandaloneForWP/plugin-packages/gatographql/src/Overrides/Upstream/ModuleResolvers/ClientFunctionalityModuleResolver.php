<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver as UpstreamClientFunctionalityModuleResolver;

class ClientFunctionalityModuleResolver extends UpstreamClientFunctionalityModuleResolver
{
    use OverrideToDisableModuleResolverTrait;
}
