<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\ModuleTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolverInterface;
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
