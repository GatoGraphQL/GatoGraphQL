<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DataStructure\DataStructureFormatterInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass;

class RegisterDataStructureFormatterCompilerPass extends AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return DataStructureManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return DataStructureFormatterInterface::class;
    }
}
