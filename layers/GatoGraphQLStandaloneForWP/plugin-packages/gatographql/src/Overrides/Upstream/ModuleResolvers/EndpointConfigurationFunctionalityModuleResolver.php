<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver as UpstreamEndpointConfigurationFunctionalityModuleResolver;

class EndpointConfigurationFunctionalityModuleResolver extends UpstreamEndpointConfigurationFunctionalityModuleResolver
{
    use OverrideToDisableModuleResolverTrait;
}