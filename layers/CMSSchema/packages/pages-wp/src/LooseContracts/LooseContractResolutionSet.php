<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:pages:date' => 'date',
        ]);
    }
}
