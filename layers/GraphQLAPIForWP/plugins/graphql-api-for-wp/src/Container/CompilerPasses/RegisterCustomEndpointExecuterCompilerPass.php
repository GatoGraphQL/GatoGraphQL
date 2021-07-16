<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\CustomEndpointExecuterServiceTagInterface;

class RegisterCustomEndpointExecuterCompilerPass extends AbstractRegisterEndpointExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CustomEndpointExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CustomEndpointExecuterServiceTagInterface::class;
    }
}
