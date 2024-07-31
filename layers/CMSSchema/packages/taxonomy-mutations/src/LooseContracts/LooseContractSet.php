<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMutations\LooseContracts;

use PoP\LooseContracts\AbstractLooseContractSet;

class LooseContractSet extends AbstractLooseContractSet
{
    public final const NAME_EDIT_TAXONOMY_TERMS_CAPABILITY = 'popcms:capability:editTaxonomies';
    public final const NAME_DELETE_TAXONOMY_TERM_CAPABILITY = 'popcms:capability:deleteTaxonomy';
    /**
     * @return string[]
     */
    public function getRequiredNames(): array
    {
        return [
            self::NAME_EDIT_TAXONOMY_TERMS_CAPABILITY,
            self::NAME_DELETE_TAXONOMY_TERM_CAPABILITY,
        ];
    }
}
