<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

use PoP\Root\Configuration\Request;
use PoP\Root\Constants\Params;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractRoutingManager implements RoutingManagerInterface
{
    use BasicServiceTrait;

    private ?RoutingHelperServiceInterface $routingHelperService = null;

    final public function setRoutingHelperService(RoutingHelperServiceInterface $routingHelperService): void
    {
        $this->routingHelperService = $routingHelperService;
    }
    final protected function getRoutingHelperService(): RoutingHelperServiceInterface
    {
        return $this->routingHelperService ??= $this->instanceManager->getInstance(RoutingHelperServiceInterface::class);
    }

    /**
     * By default, everything is a generic route
     */
    public function getCurrentRequestNature(): string
    {
        return RequestNature::GENERIC;
    }

    public function getCurrentRoute(): string
    {
        $nature = $this->getCurrentRequestNature();

        // If it is a GENERIC route, then the URL path is already the route
        if ($nature === RequestNature::GENERIC) {
            return $this->getRoutingHelperService()->getRequestURIPath();
        }

        // If having set URL param "route", then use it
        $route = Request::getRoute();
        if ($route !== null) {
            return $route;
        }

        // By default, use the "main" route
        return Routes::MAIN;
    }
}
