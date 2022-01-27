<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::addFilter('route:icon', 'popLocationpostsRouteIcon', 10, 3);
function popLocationpostsRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS:
            $fontawesome = 'fa-map-marker';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::addFilter('route:title', 'popLocationpostsNavigationRouteTitle', 10, 2);
function popLocationpostsNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS => TranslationAPIFacade::getInstance()->__('Location Posts', 'pop-locationposts'),
    ];
    return $titles[$route] ?? $title;
}
