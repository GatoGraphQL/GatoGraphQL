<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popLocationsRouteIcon', 10, 3);
function popLocationsRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_LOCATIONS_ROUTE_LOCATIONS:
        case POP_LOCATIONS_ROUTE_LOCATIONSMAP:
            $fontawesome = 'fa-map-marker';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popLocationsNavigationRouteTitle', 10, 2);
function popLocationsNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_LOCATIONS_ROUTE_LOCATIONS => TranslationAPIFacade::getInstance()->__('Locations', 'pop-locations'),
        POP_LOCATIONS_ROUTE_LOCATIONSMAP => TranslationAPIFacade::getInstance()->__('Locations Map', 'pop-locations'),
    ];
    return $titles[$route] ?? $title;
}
