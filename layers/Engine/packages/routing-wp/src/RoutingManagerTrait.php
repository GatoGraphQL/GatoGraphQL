<?php

declare(strict_types=1);

namespace PoP\RoutingWP;

use WP_Query;

trait RoutingManagerTrait
{
    private ?WP_Query $query = null;

    private function init(): void
    {
        if ($this->query === null) {
            global $wp_query;
            $this->query = $wp_query;
        }
    }

    private function isGeneric(): bool
    {
        /** @var WP_Query */
        $query = $this->query;

        // If we passed query args GENERIC_NATURE, then it's a route
        // Compare the keys only, because since PHP 8.0, comparing array values
        // (included in $query->query_vars) throws error
        return !empty(
            array_intersect(
                array_keys($query->query_vars),
                array_keys(WPQueries::GENERIC_NATURE)
            )
        );
    }
}
