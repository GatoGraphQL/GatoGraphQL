<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoP\Root\App;
use WP_Term;

use function get_term;

abstract class AbstractTaxonomyTypeAPI extends AbstractTaxonomyOrTaxonomyTermTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    protected function getTaxonomyTerm(
        string|int $taxonomyTermID,
        ?string $taxonomy = null,
    ): ?WP_Term {
        $taxonomyTerm = get_term(
            (int)$taxonomyTermID,
            $taxonomy ?? '',
        );
        if (!($taxonomyTerm instanceof WP_Term)) {
            return null;
        }
        return $taxonomyTerm;
    }

    protected function getTaxonomyTermParentID(
        string|int|WP_Term $taxonomyTermObjectOrID,
        ?string $taxonomy = null,
    ): string|int|null {
        $taxonomyTerm = $this->getTaxonomyTermFromObjectOrID(
            $taxonomyTermObjectOrID,
            $taxonomy,
        );
        if ($taxonomyTerm === null) {
            return null;
        }
        // If it has no parent, it is assigned 0. In that case, return null
        if ($parent = $taxonomyTerm->parent) {
            return $parent;
        }
        return null;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery(array $query, array $options = []): array
    {
        $query = parent::convertTaxonomyTermsQuery($query, $options);

        /**
         * If parent-id is `null` then remove the parent!
         */
        if ($this->isHierarchical() && array_key_exists('parent-id', $query)) {
            $query['parent'] = $query['parent-id'];
            unset($query['parent-id']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    abstract protected function isHierarchical(): bool;
}
