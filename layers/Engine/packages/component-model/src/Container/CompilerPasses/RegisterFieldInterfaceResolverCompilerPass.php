<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\Registries\FieldInterfaceRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass;

class RegisterFieldInterfaceResolverCompilerPass extends AbstractInjectServiceDefinitionIDIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return FieldInterfaceRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return FieldInterfaceResolverInterface::class;
    }
    protected function enabled(): bool
    {
        return ComponentConfiguration::enableSchemaEntityRegistries();
    }
}
