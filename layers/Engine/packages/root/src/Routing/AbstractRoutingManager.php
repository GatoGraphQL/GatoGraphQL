<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

use PoP\Root\App;
use PoP\Root\Constants\Params;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractRoutingManager implements RoutingManagerInterface
{
    use BasicServiceTrait;

    /**
     * By default, everything is a generic route
     */
    public function getCurrentNature(): string
    {
        return RouteNatures::GENERIC;
    }

    public function getCurrentRoute(): string
    {
        $nature = $this->getCurrentNature();

        // If it is a ROUTE, then the URL path is already the route
        if ($nature === RouteNatures::GENERIC) {
            return RoutingUtils::getURLPath();
        }

        // If having set URL param "route", then use it
        if (isset($_REQUEST[Params::ROUTE])) {
            return trim(strtolower($_REQUEST[Params::ROUTE]), '/');
        }

        // By default, use the "main" route
        return Routes::MAIN;
    }
}
