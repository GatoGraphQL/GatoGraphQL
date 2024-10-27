<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

use PoP\Root\Configuration\Request;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractRoutingManager implements RoutingManagerInterface
{
    use BasicServiceTrait;

    private ?RoutingHelperServiceInterface $routingHelperService = null;

    final protected function getRoutingHelperService(): RoutingHelperServiceInterface
    {
        if ($this->routingHelperService === null) {
            /** @var RoutingHelperServiceInterface */
            $routingHelperService = $this->instanceManager->getInstance(RoutingHelperServiceInterface::class);
            $this->routingHelperService = $routingHelperService;
        }
        return $this->routingHelperService;
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

        // By default, use the "main" route
        $default = Routes::MAIN;

        // If it is a GENERIC route, then the URL path is already the route
        if ($nature === RequestNature::GENERIC) {
            $requestURIPath = $this->getRoutingHelperService()->getRequestURIPath();
            if ($requestURIPath === null) {
                return $default;
            }
            return $requestURIPath;
        }

        // If having set URL param "route", then use it
        $route = Request::getRoute();
        if ($route !== null) {
            return $route;
        }

        return $default;
    }
}
