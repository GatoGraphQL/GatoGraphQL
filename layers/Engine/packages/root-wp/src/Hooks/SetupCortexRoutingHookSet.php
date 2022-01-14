<?php

declare(strict_types=1);

namespace PoP\RootWP\Routing\Hooks;

use Brain\Cortex\Route\QueryRoute;
use Brain\Cortex\Route\RouteCollectionInterface;
use Brain\Cortex\Route\RouteInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Routing\RoutingManagerInterface;
use PoP\RoutingWP\WPQueries;
use PoP\RoutingWP\WPQueryRoutingManagerInterface;

class SetupCortexRoutingHookSet extends AbstractHookSet
{
    private ?RoutingManagerInterface $routingManager = null;

    final public function setRoutingManager(RoutingManagerInterface $routingManager): void
    {
        $this->routingManager = $routingManager;
    }
    final protected function getRoutingManager(): RoutingManagerInterface
    {
        return $this->routingManager ??= $this->instanceManager->getInstance(RoutingManagerInterface::class);
    }

    protected function init(): void
    {
        App::addAction(
            'cortex.routes',
            [$this, 'setupCortex'],
            1
        );
    }

    /**
     * @param RouteCollectionInterface<RouteInterface> $routes
     */
    public function setupCortex(RouteCollectionInterface $routes): void
    {
        /** @var WPQueryRoutingManagerInterface */
        $routingManager = $this->getRoutingManager();
        foreach ($routingManager->getRoutes() as $route) {
            $routes->addRoute(new QueryRoute(
                $route,
                fn (array $matches) => WPQueries::GENERIC_NATURE,
            ));
        }
    }
}
