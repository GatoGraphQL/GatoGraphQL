<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popLocationpostscreationRouteIcon', 10, 3);
function popLocationpostscreationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS:
        case POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST:
        case POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST:
            $fontawesome = 'fa-map-marker';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popLocationpostscreationNavigationRouteTitle', 10, 2);
function popLocationpostscreationNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_LOCATIONPOSTSCREATION_ROUTE_MYLOCATIONPOSTS => TranslationAPIFacade::getInstance()->__('My Location Posts', 'pop-locationpostscreation'),
        POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST => TranslationAPIFacade::getInstance()->__('Add Location Post', 'pop-locationpostscreation'),
        POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST => TranslationAPIFacade::getInstance()->__('Edit Location Post', 'pop-locationpostscreation'),
    ];
    return $titles[$route] ?? $title;
}
