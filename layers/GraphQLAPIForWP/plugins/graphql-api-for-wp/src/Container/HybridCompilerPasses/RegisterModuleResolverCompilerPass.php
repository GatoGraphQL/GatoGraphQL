<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\HybridCompilerPasses;

use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\ModuleResolverInterface;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterModuleResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return ModuleRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return ModuleResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addModuleResolver';
    }
}
