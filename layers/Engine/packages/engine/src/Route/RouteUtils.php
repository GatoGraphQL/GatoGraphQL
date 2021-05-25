<?php

declare(strict_types=1);

namespace PoP\Engine\Route;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class RouteUtils
{
    public static function getRouteURL($route)
    {
        // For the route, the ID is the URI applied on the homeURL instead of the domain
        // (then, the id for domain.com/en/slug/ is "slug" and not "en/slug")
        $cmsService = CMSServiceFacade::getInstance();
        $homeurl = GeneralUtils::maybeAddTrailingSlash($cmsService->getHomeURL());
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
