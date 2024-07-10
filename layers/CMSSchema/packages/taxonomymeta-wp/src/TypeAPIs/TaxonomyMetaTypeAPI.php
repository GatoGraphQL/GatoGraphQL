<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomyMetaWP\TypeAPIs;

use PoPCMSSchema\TaxonomyMeta\TypeAPIs\AbstractTaxonomyMetaTypeAPI;
use WP_Term;

class TaxonomyMetaTypeAPI extends AbstractTaxonomyMetaTypeAPI
{
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    protected function doGetTaxonomyMeta(string|int|object $termObjectOrID, string $key, bool $single = false): mixed
    {
        if (is_object($termObjectOrID)) {
            /** @var WP_Term */
            $term = $termObjectOrID;
            $termID = $term->term_id;
        } else {
            $termID = $termObjectOrID;
        }

        // This function does not differentiate between a stored empty value,
        // and a non-existing key! So if empty, treat it as non-existent and return null
        $value = \get_term_meta((int)$termID, $key, $single);
        if (($single && $value === '') || (!$single && $value === [])) {
            return null;
        }
        return $value;
    }
}
