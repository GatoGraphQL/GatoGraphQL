<?php

declare(strict_types=1);

namespace PoP\ComponentRouting\Container\CompilerPasses;

use PoP\ComponentRouting\ComponentRoutingProcessorInterface;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterComponentRoutingProcessorCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return ComponentRoutingProcessorManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return ComponentRoutingProcessorInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addComponentRoutingProcessor';
    }
}
