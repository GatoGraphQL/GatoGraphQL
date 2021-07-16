<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\PersistedQueryEndpointAnnotatorServiceTagInterface;

class RegisterPersistedQueryEndpointAnnotatorCompilerPass extends AbstractRegisterEndpointAnnotatorCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryEndpointAnnotatorRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEndpointAnnotatorServiceTagInterface::class;
    }
}
