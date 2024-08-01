<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoPCMSSchema\TaxonomyMutations\LooseContracts\LooseContractSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            LooseContractSet::NAME_DELETE_TAXONOMY_TERM_CAPABILITY => 'delete_term',
        ]);
    }
}
