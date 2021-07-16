<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointEditorBlockServiceTagInterface;

class RegisterPersistedQueryEndpointBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryEndpointBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEndpointEditorBlockServiceTagInterface::class;
    }
}
