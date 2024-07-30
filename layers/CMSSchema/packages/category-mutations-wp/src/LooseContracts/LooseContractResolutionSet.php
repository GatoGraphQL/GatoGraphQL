<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoPCMSSchema\CategoryMutations\LooseContracts\LooseContractSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            LooseContractSet::NAME_EDIT_CATEGORYS_CAPABILITY => 'edit_posts',
            LooseContractSet::NAME_PUBLISH_CATEGORYS_CAPABILITY => 'publish_posts',
        ]);
    }
}
