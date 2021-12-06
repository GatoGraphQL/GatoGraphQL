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
    protected function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed
    {
        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existant and return null
        $value = \get_term_meta($termID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}
