<?php

declare(strict_types=1);

namespace PoP\Root\Container\HybridCompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInstantiateServiceCompilerPass;
use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;

class AutomaticallyInstantiatedServiceCompilerPass extends AbstractInstantiateServiceCompilerPass
{
    protected function onlyProcessAutoconfiguredServices(): bool
    {
        return true;
    }

    protected function getServiceClass(): string
    {
        return AutomaticallyInstantiatedServiceInterface::class;
    }
}
