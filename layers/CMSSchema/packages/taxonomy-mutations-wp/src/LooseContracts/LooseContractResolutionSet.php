<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
        ]);
    }
}
