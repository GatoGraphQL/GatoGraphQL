<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_EDIT_CUSTOMPOSTS_CAPABILITY = 'popcms:capability:editCustomPosts';
    public final const NAME_PUBLISH_CUSTOMPOSTS_CAPABILITY = 'popcms:capability:publishCustomPosts';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_EDIT_CUSTOMPOSTS_CAPABILITY,
            self::NAME_PUBLISH_CUSTOMPOSTS_CAPABILITY,
        ];
    }
}
