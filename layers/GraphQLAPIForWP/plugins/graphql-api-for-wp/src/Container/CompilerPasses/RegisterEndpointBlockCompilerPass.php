<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\EndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\EndpointEditorBlockServiceTagInterface;

class RegisterEndpointBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return EndpointBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return EndpointEditorBlockServiceTagInterface::class;
    }
}
