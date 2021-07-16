<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\CustomEndpointAnnotatorRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointAnnotators\CustomEndpointAnnotatorServiceTagInterface;

class RegisterCustomEndpointAnnotatorCompilerPass extends AbstractRegisterEndpointAnnotatorCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CustomEndpointAnnotatorRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CustomEndpointAnnotatorServiceTagInterface::class;
    }
}
