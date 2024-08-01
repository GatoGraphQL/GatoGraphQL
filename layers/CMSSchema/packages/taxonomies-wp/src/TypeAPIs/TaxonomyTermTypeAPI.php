<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoP\Root\Services\BasicServiceTrait;
use WP_Term;

class TaxonomyTermTypeAPI implements TaxonomyTermTypeAPIInterface
{
    use BasicServiceTrait;

    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(object $taxonomyTerm): string
    {
        /** @var WP_Term $taxonomyTerm */
        return $taxonomyTerm->taxonomy;
    }
    public function taxonomyTermExists(int|string $taxonomyTermIDOrSlug, string $taxonomy = ''): bool
    {
        $taxonomyTermExists = term_exists($taxonomyTermIDOrSlug, $taxonomy);
        return $taxonomyTermExists !==  null;
    }
    public function getTaxonomyTermID(int|string $taxonomyTermIDOrSlug, string $taxonomy = ''): string|int|null
    {
        /** @var array<string,string|int>|string|int|null */
        $taxonomyTerm = term_exists($taxonomyTermIDOrSlug, $taxonomy);
        if ($taxonomyTerm === null) {
            return null;
        }
        if (is_array($taxonomyTerm)) {
            /** @var string|int */
            return $taxonomyTerm['term_id'];
        }
        /** @var string|int */
        return $taxonomyTerm;
    }
}
