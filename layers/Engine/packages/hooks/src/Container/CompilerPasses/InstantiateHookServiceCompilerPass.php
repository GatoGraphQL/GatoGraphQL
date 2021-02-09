<?php

declare(strict_types=1);

namespace PoP\Hooks\Container\CompilerPasses;

use PoP\Hooks\AbstractHookSet;
use PoP\Root\Container\CompilerPasses\AbstractInstantiateServiceCompilerPass;

class InstantiateHookServiceCompilerPass extends AbstractInstantiateServiceCompilerPass
{
    protected function getServiceClass(): string
    {
        return AbstractHookSet::class;
    }
}
