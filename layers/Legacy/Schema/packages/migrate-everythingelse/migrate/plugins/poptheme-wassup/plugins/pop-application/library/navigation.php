<?php
use PoP\Root\Routing\Routes as RoutingRoutes;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::addFilter('route:icon', 'wassupRouteIcon', 10, 3);
function wassupRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case RoutingRoutes::$MAIN:
        case POP_ROUTE_DESCRIPTION:
            $fontawesome = 'fa-circle';
            break;

        case POPTHEME_WASSUP_ROUTE_SUMMARY:
            $fontawesome = 'fa-star';
            break;

        case POP_ROUTE_AUTHORS:
            $fontawesome = 'fa-user';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::addFilter('route:title', 'wassupNavigationRouteTitle', 10, 2);
function wassupNavigationRouteTitle($title, $route)
{
    $titles = [
        RoutingRoutes::$MAIN => TranslationAPIFacade::getInstance()->__('Main', 'poptheme-wassup'),
        POP_ROUTE_DESCRIPTION => TranslationAPIFacade::getInstance()->__('Description', 'poptheme-wassup'),
        POPTHEME_WASSUP_ROUTE_SUMMARY => TranslationAPIFacade::getInstance()->__('Summary', 'poptheme-wassup'),
        POP_ROUTE_AUTHORS => TranslationAPIFacade::getInstance()->__('Authors', 'poptheme-wassup'),
    ];
    return $titles[$route] ?? $title;
}
