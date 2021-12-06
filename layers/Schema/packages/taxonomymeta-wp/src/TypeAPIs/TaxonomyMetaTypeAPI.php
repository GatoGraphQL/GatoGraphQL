<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMetaWP\TypeAPIs;

use PoPSchema\TaxonomyMeta\TypeAPIs\AbstractTaxonomyMetaTypeAPI;

class TaxonomyMetaTypeAPI extends AbstractTaxonomyMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    public function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed
    {
        $value = \get_term_meta($termID, $key, $single);
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
