<?php

declare(strict_types=1);

namespace PoP\RoutingWP\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use Brain\Cortex\Route\QueryRoute;
use Brain\Cortex\Route\RouteCollectionInterface;
use Brain\Cortex\Route\RouteInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Routing\Facades\RoutingManagerFacade;
use PoP\Routing\RoutingManagerInterface;
use PoP\RoutingWP\WPQueries;
use PoP\Translation\TranslationAPIInterface;

class SetupCortexHookSet extends AbstractHookSet
{
    protected RoutingManagerInterface $routingManager;

    #[Required]
    public function autowireSetupCortexHookSet(
        RoutingManagerInterface $routingManager,
    ) {
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
