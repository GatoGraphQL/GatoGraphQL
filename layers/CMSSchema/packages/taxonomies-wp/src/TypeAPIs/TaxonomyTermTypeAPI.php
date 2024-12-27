<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoP\Root\Services\AbstractBasicService;
use WP_Error;
use WP_Taxonomy;
use WP_Term;

use function get_term;
use function get_object_taxonomies;
use function get_taxonomy;

class TaxonomyTermTypeAPI extends AbstractBasicService implements TaxonomyTermTypeAPIInterface
{
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(object $taxonomyTerm): string
    {
        /** @var WP_Term $taxonomyTerm */
        return $taxonomyTerm->taxonomy;
    }
    public function taxonomyTermExists(int|string $taxonomyTermIDOrSlug, ?string $taxonomy = null): bool
    {
        $taxonomyTermExists = term_exists($taxonomyTermIDOrSlug, $taxonomy ?? '');
        return $taxonomyTermExists !==  null;
    }
    public function getTaxonomyTermID(string $taxonomyTermSlug, ?string $taxonomy = null): string|int|null
    {
        /** @var array<string,string|int>|string|int|null */
        $taxonomyTerm = term_exists($taxonomyTermSlug, $taxonomy ?? '');
        if ($taxonomyTerm === null) {
            return null;
        }

        /**
         * Must cast the ID to integer, to avoid a string "34"
         * from being inserted as a new term.
         */
        if (is_array($taxonomyTerm)) {
            return (int) $taxonomyTerm['term_id'];
        }
        return (int) $taxonomyTerm;
    }

    public function getTaxonomyTermTaxonomy(int|string $taxonomyTermID): string|null
    {
        /** @var WP_Term|null */
        $taxonomyTerm = $this->getTaxonomyTerm($taxonomyTermID);
        if ($taxonomyTerm === null) {
            return null;
        }
        return $taxonomyTerm->taxonomy;
    }

    public function getTaxonomyTerm(int|string $taxonomyTermID, ?string $taxonomy = null): object|null
    {
        /** @var WP_Term|WP_Error|null */
        $taxonomyTerm = get_term((int) $taxonomyTermID, $taxonomy ?? '');
        if ($taxonomyTerm instanceof WP_Error) {
            return null;
        }
        return $taxonomyTerm;
    }

    public function canUserEditTaxonomy(string|int $userID, string $taxonomyName): bool
    {
        /** @var WP_Taxonomy */
        $taxonomy = $this->getTaxonomy($taxonomyName);
        return isset($taxonomy->cap->edit_terms) && user_can((int) $userID, $taxonomy->cap->edit_terms);
    }

    public function canUserAssignTermsToTaxonomy(string|int $userID, string $taxonomyName): bool
    {
        /** @var WP_Taxonomy */
        $taxonomy = $this->getTaxonomy($taxonomyName);
        return isset($taxonomy->cap->assign_terms) && user_can((int) $userID, $taxonomy->cap->assign_terms);
    }

    public function canUserDeleteTaxonomyTerm(string|int $userID, string|int $taxonomyTermID): bool
    {
        return user_can((int) $userID, 'delete_term', $taxonomyTermID);
    }

    public function getTaxonomy(string $taxonomyName): object|null
    {
        $taxonomy = get_taxonomy($taxonomyName);
        if ($taxonomy === false) {
            return null;
        }
        return $taxonomy;
    }

    public function taxonomyExists(string $taxonomyName): bool
    {
        return $this->getTaxonomy($taxonomyName) !== null;
    }

    /**
     * @return string[]
     */
    public function getCustomPostTypeTaxonomyNames(string $customPostType): array
    {
        return get_object_taxonomies($customPostType);
    }
    public function isTaxonomyHierarchical(string $taxonomyName): ?bool
    {
        $taxonomy = get_taxonomy($taxonomyName);
        if ($taxonomy === false) {
            return null;
        }
        return $taxonomy->hierarchical;
    }
}
