<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoPCMSSchema\PageMutations\LooseContracts\LooseContractSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            LooseContractSet::NAME_EDIT_PAGES_CAPABILITY => 'edit_pages',
            LooseContractSet::NAME_PUBLISH_PAGES_CAPABILITY => 'publish_pages',
        ]);
    }
}
