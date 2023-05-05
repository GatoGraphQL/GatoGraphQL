<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\HybridCompilerPasses;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
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
