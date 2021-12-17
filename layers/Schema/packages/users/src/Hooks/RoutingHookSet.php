<?php

declare(strict_types=1);

namespace PoPSchema\Users\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoP\Routing\RouteHookNames;
use PoPSchema\Users\ComponentConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            RouteHookNames::ROUTES,
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(array $routes): array
    {
        return [
            ...$routes,
            ComponentConfiguration::getUsersRoute(),
        ];
    }
}
