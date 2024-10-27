<?php

declare(strict_types=1);

namespace PoP\RootWP\Hooks;

use Brain\Cortex\Route\QueryRoute;
use Brain\Cortex\Route\RouteCollectionInterface;
use Brain\Cortex\Route\RouteInterface;
use PoP\RootWP\Routing\WPQueries;
use PoP\RootWP\Routing\WPQueryRoutingManagerInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Module;
use PoP\Root\ModuleConfiguration;
use PoP\Root\Routing\RoutingManagerInterface;

class SetupCortexRoutingHookSet extends AbstractHookSet
{
    private ?RoutingManagerInterface $routingManager = null;

    final protected function getRoutingManager(): RoutingManagerInterface
    {
        if ($this->routingManager === null) {
            /** @var RoutingManagerInterface */
            $routingManager = $this->instanceManager->getInstance(RoutingManagerInterface::class);
            $this->routingManager = $routingManager;
        }
        return $this->routingManager;
    }

    protected function init(): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enablePassingRoutingStateViaRequest()) {
            return;
        }

        /**
         * Gato GraphQL plugin: This code is never reached!
         *
         * @see layers/Engine/packages/root-wp/src/Module.php
         */
        App::addAction(
            'cortex.routes',
            $this->setupCortex(...),
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
