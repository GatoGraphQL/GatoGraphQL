<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\EndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;

class RegisterEndpointExecuterCompilerPass extends AbstractRegisterEndpointExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return EndpointExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return EndpointExecuterServiceTagInterface::class;
    }
}
