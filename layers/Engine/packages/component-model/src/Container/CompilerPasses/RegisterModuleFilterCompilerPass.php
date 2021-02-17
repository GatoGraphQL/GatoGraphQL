<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ModuleFiltering\ModuleFilterManagerInterface;
use PoP\ComponentModel\ModuleFilters\ModuleFilterInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass;

class RegisterModuleFilterCompilerPass extends AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return ModuleFilterManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return ModuleFilterInterface::class;
    }
}
