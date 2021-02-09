<?php

declare(strict_types=1);

namespace PoP\LooseContracts\Container\CompilerPasses;

use PoP\LooseContracts\AbstractLooseContractSet;
use PoP\Root\Container\CompilerPasses\AbstractInstantiateServiceCompilerPass;

class InstantiateLooseContractSetServiceCompilerPass extends AbstractInstantiateServiceCompilerPass
{
    protected function getServiceClass(): string
    {
        return AbstractLooseContractSet::class;
    }
}
