<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\ModuleTypeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterModuleTypeResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return ModuleTypeRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return ModuleTypeResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addModuleTypeResolver';
    }
}
