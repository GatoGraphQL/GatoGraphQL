<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutationsWP\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoPCMSSchema\MediaMutations\LooseContracts\LooseContractSet;

class LooseContractResolutionSet extends AbstractLooseContractResolutionSet
{
    protected function resolveContracts(): void
    {
        $this->getNameResolver()->implementNames([
            LooseContractSet::NAME_UPLOAD_FILES_CAPABILITY => 'upload_files',
            LooseContractSet::NAME_EDIT_OTHER_USERS_CUSTOMPOSTS_CAPABILITY => 'edit_others_posts',
        ]);
    }
}
