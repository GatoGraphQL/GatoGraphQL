<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;
use PoP\Root\Registries\AppStateProviderRegistryInterface;
use PoP\Root\State\AppStateProviderInterface;

class RegisterAppStateProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return AppStateProviderRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return AppStateProviderInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addAppStateProvider';
    }
}
