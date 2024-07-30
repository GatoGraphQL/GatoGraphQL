<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_EDIT_TAXONOMIES_CAPABILITY = 'popcms:capability:editTaxonomies';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_EDIT_TAXONOMIES_CAPABILITY,
        ];
    }
}
