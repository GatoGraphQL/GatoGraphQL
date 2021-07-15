<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigEditorBlockServiceTagInterface;

class RegisterSchemaConfigBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return SchemaConfigBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return SchemaConfigEditorBlockServiceTagInterface::class;
    }
}
