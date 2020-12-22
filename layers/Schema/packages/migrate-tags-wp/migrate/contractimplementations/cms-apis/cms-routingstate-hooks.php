<?php
namespace PoPSchema\Tags\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Tags\Routing\RouteNatures;
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
        if ($query->is_tag()) {
            return RouteNatures::TAG;
        }

        return $nature;
    }
}

/**
 * Initialize
 */
new WPCMSRoutingStateHooks();
