<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_EDIT_CATEGORYS_CAPABILITY = 'popcms:capability:editCustomPosts';
    public final const NAME_PUBLISH_CATEGORYS_CAPABILITY = 'popcms:capability:publishCustomPosts';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_EDIT_CATEGORYS_CAPABILITY,
            self::NAME_PUBLISH_CATEGORYS_CAPABILITY,
        ];
    }
}
