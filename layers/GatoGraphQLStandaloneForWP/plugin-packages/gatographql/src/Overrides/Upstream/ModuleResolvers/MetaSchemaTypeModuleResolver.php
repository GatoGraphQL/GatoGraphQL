<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver as UpstreamMetaSchemaTypeModuleResolver;

class MetaSchemaTypeModuleResolver extends UpstreamMetaSchemaTypeModuleResolver
{
    use OverrideToDisableModuleResolverTrait;
}
