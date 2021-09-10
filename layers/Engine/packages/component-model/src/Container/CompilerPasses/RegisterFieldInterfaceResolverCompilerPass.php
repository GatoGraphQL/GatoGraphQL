<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldInterfaceResolvers\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterFieldInterfaceResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return FieldInterfaceRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return InterfaceTypeFieldResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addFieldInterfaceResolver';
    }
    protected function enabled(): bool
    {
        return ComponentConfiguration::enableSchemaEntityRegistries();
    }
}
