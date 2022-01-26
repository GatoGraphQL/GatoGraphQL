<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DataStructureFormatters\DataStructureFormatterInterface;
use PoP\ComponentModel\DataStructure\DataStructureManagerInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterDataStructureFormatterCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return DataStructureManagerInterface::class;
    }
    protected function getServiceClass(): string
    {
        return DataStructureFormatterInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addDataStructureFormatter';
    }
}
