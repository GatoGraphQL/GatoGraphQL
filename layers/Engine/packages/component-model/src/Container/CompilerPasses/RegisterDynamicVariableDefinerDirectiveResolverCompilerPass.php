<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\DirectiveResolvers\DynamicVariableDefinerFieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DynamicVariableDefinerDirectiveRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterDynamicVariableDefinerFieldDirectiveResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return DynamicVariableDefinerDirectiveRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return DynamicVariableDefinerFieldDirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addDynamicVariableDefinerFieldDirectiveResolver';
    }
}
