<?php

declare(strict_types=1);

namespace PoP\RoutingWP;

use PoP\Root\Routing\RoutingManagerInterface;

interface WPQueryRoutingManagerInterface extends RoutingManagerInterface
{
    /**
     * All the routes defined in the application.
     *
     * @return string[]
     */
    public function getRoutes(): array;
}
