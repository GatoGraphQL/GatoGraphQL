<?php

declare(strict_types=1);

namespace PoP\RootWP\Routing;

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
