<?php

declare(strict_types=1);

namespace PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use function add_filter;
use function remove_filter;

trait StableTaxonomyTermOrderTrait
{
    /**
     * Execute a term-fetching callback with a deterministic secondary
     * ordering by term ID, so that terms sharing the same primary sort
     * value (eg: terms with a duplicate name) are always returned in a
     * stable order, instead of relying on the database's arbitrary order
     * for ties.
     *
     * The tiebreaker is applied via the `terms_clauses` filter, so it works
     * for `get_terms()` and its wrappers (`get_categories()`, `get_tags()`),
     * preserving the primary sort direction.
     *
     * @template T
     * @param callable(): T $executeQueryCallback
     * @return T
     */
    protected function executeTermQueryWithStableOrder(callable $executeQueryCallback): mixed
    {
        /**
         * @param array<string,string> $clauses
         * @return array<string,string>
         */
        $addTermIDTiebreaker = static function (array $clauses): array {
            $orderby = $clauses['orderby'] ?? '';
            if ($orderby === '' || str_contains($orderby, 't.term_id')) {
                return $clauses;
            }
            $order = trim($clauses['order'] ?? '');
            $columns = (string) preg_replace('/^ORDER BY\s+/i', '', $orderby);
            $tiebreakerOrder = $order !== '' ? $order : 'ASC';
            $clauses['orderby'] = sprintf('ORDER BY %s %s, t.term_id %s', $columns, $order, $tiebreakerOrder);
            $clauses['order'] = '';
            return $clauses;
        };
        add_filter('terms_clauses', $addTermIDTiebreaker);
        try {
            return $executeQueryCallback();
        } finally {
            remove_filter('terms_clauses', $addTermIDTiebreaker);
        }
    }
}
