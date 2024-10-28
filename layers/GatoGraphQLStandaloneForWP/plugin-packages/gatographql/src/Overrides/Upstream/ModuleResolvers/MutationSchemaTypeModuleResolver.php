<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MutationSchemaTypeModuleResolver as UpstreamMutationSchemaTypeModuleResolver;

class MutationSchemaTypeModuleResolver extends UpstreamMutationSchemaTypeModuleResolver
{
    use OverrideToDisableModuleResolverTrait;
}
