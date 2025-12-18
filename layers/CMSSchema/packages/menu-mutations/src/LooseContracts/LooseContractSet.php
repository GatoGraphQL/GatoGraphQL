<?php

declare(strict_types=1);

namespace PoPCMSSchema\MenuMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_UPLOAD_FILES_CAPABILITY = 'popcms:capability:uploadFiles';
    public final const NAME_UPLOAD_FILES_FOR_OTHER_USERS_CAPABILITY = 'popcms:capability:uploadFilesForOtherUsers';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_UPLOAD_FILES_CAPABILITY,
            self::NAME_UPLOAD_FILES_FOR_OTHER_USERS_CAPABILITY,
        ];
    }
}
