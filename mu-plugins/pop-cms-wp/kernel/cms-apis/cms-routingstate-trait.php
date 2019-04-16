<?php
namespace PoP\CMS\WP;

trait WPCMSRoutingStateTrait
{
    private $query;

    private function init()
    {
        if (is_null($this->query)) {
            global $wp_query;
            $this->query = $wp_query;
        }
    }

    private function isStandard() {

        // If we passed query args STANDARDNATURE_ROUTE_QUERY, then it's a route
        return !empty(array_intersect($this->query->query_vars, STANDARDNATURE_ROUTE_QUERY));
    }
}