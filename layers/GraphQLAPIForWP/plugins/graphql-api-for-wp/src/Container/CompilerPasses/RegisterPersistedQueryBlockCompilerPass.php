<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEditorBlockServiceTagInterface;

class RegisterPersistedQueryBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEditorBlockServiceTagInterface::class;
    }
}
