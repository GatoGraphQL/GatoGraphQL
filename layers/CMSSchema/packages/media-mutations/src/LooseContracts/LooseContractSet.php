<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_UPLOAD_FILES_CAPABILITY = 'popcms:capability:uploadFiles';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_UPLOAD_FILES_CAPABILITY,
        ];
    }
}
