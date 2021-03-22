<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutationsWP\LooseContracts;

use PoPSchema\CustomPostMutations\LooseContracts\LooseContractSet;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->nameResolver->implementNames([
            LooseContractSet::NAME_EDIT_CUSTOMPOSTS_CAPABILITY => 'edit_posts',
            LooseContractSet::NAME_PUBLISH_CUSTOMPOSTS_CAPABILITY => 'publish_posts',
        ]);
    }
}
