<?php

declare(strict_types=1);

namespace PoP\Engine\Route;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;

class RouteUtils
{

    public static function getRouteURL($route)
    {
        // For the route, the ID is the URI applied on the homeURL instead of the domain
        // (then, the id for domain.com/en/slug/ is "slug" and not "en/slug")
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $homeurl = GeneralUtils::maybeAddTrailingSlash($cmsengineapi->getHomeURL());
        return $homeurl . $route . '/';
    }

    public static function getRouteTitle($route)
    {
        $title = HooksAPIFacade::getInstance()->applyFilters(
            'route:title',
            $route,
            $route
        );
        return $title;
    }
}
