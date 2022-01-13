<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popAddlocationsRouteIcon', 10, 3);
function popAddlocationsRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_ADDLOCATIONS_ROUTE_ADDLOCATION:
            $fontawesome = 'fa-map-marker';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popAddlocationsNavigationRouteTitle', 10, 2);
function popAddlocationsNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_ADDLOCATIONS_ROUTE_ADDLOCATION => TranslationAPIFacade::getInstance()->__('Add Location', 'pop-addlocations'),
    ];
    return $titles[$route] ?? $title;
}
