<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Misc;

use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Routing\URLParams;

class RequestUtils
{
    public static array $errors = [];

    public static function getDomainId($domain)
    {
        // The domain ID is simply removing the scheme, and replacing all dots with '-'
        // It is needed to assign an extra class to the event
        $domain_id = str_replace('.', '-', removeScheme($domain));

        // Allow to override the domainId, to unify DEV and PROD domains
        return HooksAPIFacade::getInstance()->applyFilters('pop_modulemanager:domain_id', $domain_id, $domain);
    }

    public static function isSearchEngine()
    {
        return HooksAPIFacade::getInstance()->applyFilters('RequestUtils:isSearchEngine', false);
    }

    public static function getFramecomponentModules()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'RequestUtils:getFramecomponentModules',
            array()
        );
    }

    public static function addRoute($url, $route)
    {
        return GeneralUtils::addQueryArgs([URLParams::ROUTE => $route], $url);
    }

    public static function fetchingSite()
    {
        $vars = ApplicationState::getVars();
        return \PoP\Root\App::getState('fetching-site') ?? false;
    }

    public static function loadingSite()
    {
        // If we are doing JSON (or any other output) AND we setting the target, then we're loading content dynamically and we need it to be JSON
        // Otherwise, it is the first time loading website => loadingSite
        $vars = ApplicationState::getVars();
        return \PoP\Root\App::getState('loading-site') ?? false;
    }

    public static function isRoute($route_or_routes)
    {
        $vars = ApplicationState::getVars();
        $route = \PoP\Root\App::getState('route') ?? null;
        if (is_array($route_or_routes)) {
            return in_array($route, $route_or_routes);
        }

        return $route == $route_or_routes;
    }
}
