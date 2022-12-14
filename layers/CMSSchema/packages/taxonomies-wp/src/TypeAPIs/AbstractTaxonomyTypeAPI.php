<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use WP_Error;
use WP_Term;

use function get_term;

abstract class AbstractTaxonomyTypeAPI
{
    use BasicServiceTrait;
    
    protected function getTerm(string|int $termObjectID, string $taxonomy = ''): ?WP_Term
    {
        $term = get_term((int)$termObjectID, $taxonomy);
        if ($term instanceof WP_Error) {
            return null;
        }
        /** @var WP_Term */
        return $term;
    }
}
