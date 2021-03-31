<?php
namespace PoPSchema\Categories\WP;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Categories\Routing\RouteNatures;
use WP_Query;

class WPCMSRoutingStateHooks
{
    public function __construct() {

        HooksAPIFacade::getInstance()->addFilter(
            'WPCMSRoutingState:nature',
            [$this, 'getNature'],
            10,
            2
        );
    }

    /**
     * The nature of the route
     */
    public function getNature(string $nature, WP_Query $query): string
    {
        if ($query->is_category()) {
            return RouteNatures::CATEGORY;
        }

        return $nature;
    }
}

/**
 * Initialize
 */
new WPCMSRoutingStateHooks();
