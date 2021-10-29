<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY => 'edit_posts',
            LooseContractSet::NAME_PUBLISH_CUSTOMPOSTS_CAPABILITY => 'publish_posts',
        ]);
    }
}
