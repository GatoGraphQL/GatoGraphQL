<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DirectiveResolvers\MetaFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\MetaDirectiveRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterMetaFieldDirectiveResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return MetaDirectiveRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return MetaFieldDirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addMetaFieldDirectiveResolver';
    }
}
