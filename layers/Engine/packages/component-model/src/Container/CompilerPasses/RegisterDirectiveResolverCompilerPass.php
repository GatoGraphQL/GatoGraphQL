<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceClassIntoRegistryCompilerPass;

class RegisterDirectiveResolverCompilerPass extends AbstractInjectServiceClassIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return DirectiveRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return DirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addDirectiveResolverClass';
    }
    protected function enabled(): bool
    {
        return ComponentConfiguration::enableSchemaEntityRegistries();
    }
}
