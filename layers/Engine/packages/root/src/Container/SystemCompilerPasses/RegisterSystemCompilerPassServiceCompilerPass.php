<?php

declare(strict_types=1);

namespace PoP\Root\Container\SystemCompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
use PoP\Root\Registries\CompilerPassRegistryInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RegisterSystemCompilerPassServiceCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CompilerPassRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CompilerPassInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addCompilerPass';
    }
}
