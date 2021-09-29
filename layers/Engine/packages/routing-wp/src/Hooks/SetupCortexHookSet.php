<?php

declare(strict_types=1);

namespace PoP\RoutingWP\Hooks;

use Brain\Cortex\Route\QueryRoute;
use Brain\Cortex\Route\RouteCollectionInterface;
use Brain\Cortex\Route\RouteInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Routing\RoutingManagerInterface;
use PoP\RoutingWP\WPQueries;
use Symfony\Contracts\Service\Attribute\Required;

class SetupCortexHookSet extends AbstractHookSet
{
    protected RoutingManagerInterface $routingManager;

    #[Required]
    public function autowireSetupCortexHookSet(
        RoutingManagerInterface $routingManager,
    ): void {
        $this->routingManager = $routingManager;
    }

    protected function init(): void
    {
        $this->hooksAPI->addAction(
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
        foreach ($this->routingManager->getRoutes() as $route) {
            $routes->addRoute(new QueryRoute(
                $route,
                function (array $matches) {
                    return WPQueries::STANDARD_NATURE;
                }
            ));
        }
    }
}
