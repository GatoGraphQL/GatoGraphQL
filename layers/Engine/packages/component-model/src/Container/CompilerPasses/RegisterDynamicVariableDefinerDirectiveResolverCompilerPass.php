<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterDynamicVariableDefinerDirectiveResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return DynamicVariableDefinerDirectiveRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return DynamicVariableDefinerDirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addDynamicVariableDefinerDirectiveResolver';
    }
}
