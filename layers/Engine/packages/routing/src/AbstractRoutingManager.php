<?php

declare(strict_types=1);

namespace PoP\Routing;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Routing\URLParams;
use PoP\Routing\RoutingUtils;
use PoP\Hooks\HooksAPIInterface;

abstract class AbstractRoutingManager implements RoutingManagerInterface
{
    /**
     * @var string[]|null
     */
    private ?array $routes = null;
    protected HooksAPIInterface $hooksAPI;

    #[Required]
    public function autowireAbstractRoutingManager(HooksAPIInterface $hooksAPI)
    {
        $this->hooksAPI = $hooksAPI;
    }

    /**
     * @return string[]
     */
    public function getRoutes(): array
    {
        if (is_null($this->routes)) {
            $this->routes = array_filter(
                (array) $this->hooksAPI->applyFilters(
                    RouteHookNames::ROUTES,
                    []
                )
            );

            // // If there are partial endpoints, generate all the combinations of route + partial endpoint
            // // For instance, route = "posts", endpoint = "/api/rest", combined route = "posts/api/rest"
            // if ($partialEndpoints = array_filter(
            //     (array) $this->hooksAPI->applyFilters(
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

    public function getCurrentNature(): string
    {
        // By default, everything is a standard route
        return RouteNatures::STANDARD;
    }

    public function getCurrentRoute(): string
    {
        $nature = $this->getCurrentNature();

        // If it is a ROUTE, then the URL path is already the route
        if ($nature == RouteNatures::STANDARD) {
            $route = RoutingUtils::getURLPath();
        } else {
            // If having set URL param "route", then use it
            if (isset($_REQUEST[URLParams::ROUTE])) {
                $route = trim(strtolower($_REQUEST[URLParams::ROUTE]), '/');
            } else {
                // If not, use the "main" route
                $route = Routes::$MAIN;
            }
        }

        // Allow to change it
        return (string) $this->hooksAPI->applyFilters(
            RouteHookNames::CURRENT_ROUTE,
            $route,
            $nature
        );
    }
}
