<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoPCMSSchema\MenuMutations\LooseContracts\LooseContractSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            LooseContractSet::NAME_CREATE_MENUS_CAPABILITY => 'upload_files',
            LooseContractSet::NAME_CREATE_MENUS_FOR_OTHER_USERS_CAPABILITY => 'edit_others_posts',
        ]);
    }
}
