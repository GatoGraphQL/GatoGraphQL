<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_EDIT_PAGES_CAPABILITY = 'popcms:capability:editPages';
    public final const NAME_PUBLISH_PAGES_CAPABILITY = 'popcms:capability:publishPages';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_EDIT_PAGES_CAPABILITY,
            self::NAME_PUBLISH_PAGES_CAPABILITY,
        ];
    }
}
