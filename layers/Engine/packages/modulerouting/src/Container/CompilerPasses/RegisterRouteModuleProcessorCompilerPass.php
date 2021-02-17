<?php

declare(strict_types=1);

namespace PoP\ModuleRouting\Container\CompilerPasses;

use PoP\ModuleRouting\AbstractRouteModuleProcessor;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass;

class RegisterRouteModuleProcessorCompilerPass extends AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return RouteModuleProcessorManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return AbstractRouteModuleProcessor::class;
    }
}
