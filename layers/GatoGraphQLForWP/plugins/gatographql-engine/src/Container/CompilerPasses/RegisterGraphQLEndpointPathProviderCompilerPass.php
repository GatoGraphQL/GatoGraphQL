<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\GraphQLEndpointPathProviderRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\GraphQLEndpointPathProviders\GraphQLEndpointPathProviderInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterGraphQLEndpointPathProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return GraphQLEndpointPathProviderRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return GraphQLEndpointPathProviderInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addGraphQLEndpointPathProvider';
    }
}
