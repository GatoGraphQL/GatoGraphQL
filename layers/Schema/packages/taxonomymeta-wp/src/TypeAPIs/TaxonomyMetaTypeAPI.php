<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomyMetaWP\TypeAPIs;

use PoPSchema\TaxonomyMeta\TypeAPIs\AbstractTaxonomyMetaTypeAPI;

class TaxonomyMetaTypeAPI extends AbstractTaxonomyMetaTypeAPI
{
    public function doGetTaxonomyMeta(string | int $termID, string $key, bool $single = false): mixed
    {
        return \get_term_meta($termID, $key, $single);
    }
}
