<?php

declare(strict_types=1);

namespace PoPSchema\TagsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:tags:count' => 'count',
            'popcms:dbcolumn:orderby:tags:id' => 'term_id',
        ]);
    }
}
