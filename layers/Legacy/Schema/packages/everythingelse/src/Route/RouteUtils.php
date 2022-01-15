<?php

declare(strict_types=1);

namespace PoP\Engine\Route;

use PoP\Root\App;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoPSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

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
        $title = App::applyFilters(
            'route:title',
            $route,
            $route
        );
        return $title;
    }
}
