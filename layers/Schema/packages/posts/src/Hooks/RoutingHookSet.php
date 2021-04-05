<?php

declare(strict_types=1);

namespace PoPSchema\Posts\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\Routing\RouteHookNames;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            RouteHookNames::ROUTES,
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(array $routes): array
    {
        return [
            ...$routes,
            \POP_POSTS_ROUTE_POSTS,
        ];
    }
}
