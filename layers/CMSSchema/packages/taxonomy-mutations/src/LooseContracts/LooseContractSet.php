<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_EDIT_TAXONOMIES_CAPABILITY = 'popcms:capability:editCustomPosts';
    public final const NAME_PUBLISH_TAXONOMIES_CAPABILITY = 'popcms:capability:publishCustomPosts';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_EDIT_TAXONOMIES_CAPABILITY,
            self::NAME_PUBLISH_TAXONOMIES_CAPABILITY,
        ];
    }
}
