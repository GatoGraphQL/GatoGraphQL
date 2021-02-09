<?php

declare(strict_types=1);

namespace PoP\LooseContracts\Container\CompilerPasses;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoP\Root\Container\CompilerPasses\AbstractInstantiateServiceCompilerPass;

class InstantiateLooseContractResolutionSetServiceCompilerPass extends AbstractInstantiateServiceCompilerPass
{
    protected function getServiceClass(): string
    {
        return AbstractLooseContractResolutionSet::class;
    }
}
