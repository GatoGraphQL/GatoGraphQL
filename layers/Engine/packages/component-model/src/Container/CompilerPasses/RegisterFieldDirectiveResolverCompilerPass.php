<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\FieldDirectiveResolverRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterFieldDirectiveResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return FieldDirectiveResolverRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return FieldDirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addFieldDirectiveResolver';
    }
}
