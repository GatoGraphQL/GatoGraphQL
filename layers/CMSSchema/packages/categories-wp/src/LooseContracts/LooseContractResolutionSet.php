<?php

declare(strict_types=1);

namespace PoPSchema\CategoriesWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            'popcms:dbcolumn:orderby:categories:count' => 'count',
            'popcms:dbcolumn:orderby:categories:id' => 'term_id',
        ]);
    }
}
