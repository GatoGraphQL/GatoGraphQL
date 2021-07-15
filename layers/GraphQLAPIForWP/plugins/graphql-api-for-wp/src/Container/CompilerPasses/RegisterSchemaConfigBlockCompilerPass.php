<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigBlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigEditorBlockServiceTagInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterSchemaConfigBlockCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return SchemaConfigBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return SchemaConfigEditorBlockServiceTagInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addBlock';
    }
}
