<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

/**
 * Basic Routing Manager, needed for PHPUnit.
 * Must be overriden by the CMS implementation.
 */
class BasicRoutingManager extends AbstractRoutingManager
{
    /**
     * By default, everything is a generic route
     */
    public function getCurrentNature(): string
    {
        return RouteNatures::GENERIC;
    }
}
