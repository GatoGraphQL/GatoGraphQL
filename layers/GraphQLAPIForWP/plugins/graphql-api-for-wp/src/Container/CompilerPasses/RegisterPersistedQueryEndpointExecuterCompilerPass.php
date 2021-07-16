<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\PersistedQueryEndpointExecuterServiceTagInterface;

class RegisterPersistedQueryEndpointExecuterCompilerPass extends AbstractRegisterEndpointExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryEndpointExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEndpointExecuterServiceTagInterface::class;
    }
}
