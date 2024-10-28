<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver as UpstreamEndpointFunctionalityModuleResolver;

class EndpointFunctionalityModuleResolver extends UpstreamEndpointFunctionalityModuleResolver
{
    use OverrideToDisableModuleResolverTrait;
}
