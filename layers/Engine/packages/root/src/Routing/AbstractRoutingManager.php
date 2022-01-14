<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

use PoP\Root\App;
use PoP\Root\Constants\Params;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractRoutingManager implements RoutingManagerInterface
{
    use BasicServiceTrait;

    /**
     * @var string[]|null
     */
    private ?array $routes = null;

    /**
     * @return string[]
     */
    public function getRoutes(): array
    {
        if ($this->routes === null) {
            $this->routes = array_filter(
                (array) App::applyFilters(
                    RouteHookNames::ROUTES,
                    []
                )
            );

            // // If there are partial endpoints, generate all the combinations of route + partial endpoint
            // // For instance, route = "posts", endpoint = "/api/rest", combined route = "posts/api/rest"
            // if ($partialEndpoints = array_filter(
            //     (array) \PoP\Root\App::applyFilters(
            //         'route-endpoints',
            //         []
            //     )
            // )) {
            //     // Attach the endpoints to each of the routes
            //     $routes = $this->routes;
            //     foreach ($routes as $route) {
            //         foreach ($partialEndpoints as $endpoint) {
            //             $this->routes[] = $route . '/' . trim($endpoint, '/');
            //         }
            //     }
            // }
        }
        return $this->routes;
    }

    public function getCurrentRoute(): string
    {
        $nature = $this->getCurrentNature();

        // If it is a ROUTE, then the URL path is already the route
        if ($nature === RouteNatures::GENERIC) {
            $route = RoutingUtils::getURLPath();
        } else {
            // If having set URL param "route", then use it
            if (isset($_REQUEST[Params::ROUTE])) {
                $route = trim(strtolower($_REQUEST[Params::ROUTE]), '/');
            } else {
                // If not, use the "main" route
                $route = Routes::MAIN;
            }
        }

        // Allow to change it
        return (string) App::applyFilters(
            RouteHookNames::CURRENT_ROUTE,
            $route,
            $nature
        );
    }
}
