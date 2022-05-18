<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\ComponentFilters\ComponentFilterInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterComponentFilterCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return ComponentFilterManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return ComponentFilterInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addComponentFilter';
    }
}
