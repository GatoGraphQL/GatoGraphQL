<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Misc;

use PoP\Root\App;
use PoP\Root\Constants\Params;

class RequestUtils
{
    public static array $errors = [];

    public static function getDomainID(string $domain): string
    {
        // The domain ID is simply removing the scheme, and replacing all dots with '-'
        // It is needed to assign an extra class to the event
        $domain_id = str_replace('.', '-', removeScheme($domain));

        // Allow to override the domainId, to unify DEV and PROD domains
        return App::applyFilters('pop_componentmanager:domain_id', $domain_id, $domain);
    }

    public static function isSearchEngine()
    {
        return App::applyFilters('RequestUtils:isSearchEngine', false);
    }

    public static function getFramecomponentComponents()
    {
        return App::applyFilters(
            'RequestUtils:getFramecomponentModules',
            array()
        );
    }

    public static function addRoute(string $url, string $route): string
    {
        return GeneralUtils::addQueryArgs([Params::ROUTE => $route], $url);
    }

    public static function fetchingSite()
    {
        return App::getState('fetching-site') ?? false;
    }

    public static function loadingSite()
    {
        // If we are doing JSON (or any other output) AND we setting the target, then we're loading content dynamically and we need it to be JSON
        // Otherwise, it is the first time loading website => loadingSite
        return App::getState('loading-site') ?? false;
    }

    public static function isRoute(string|array $route_or_routes): bool
    {
        $route = App::getState('route');
        if (is_array($route_or_routes)) {
            return in_array($route, $route_or_routes);
        }

        return $route == $route_or_routes;
    }
}
