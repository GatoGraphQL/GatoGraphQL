<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popLocationpostlinkscreationRouteIcon', 10, 3);
function popLocationpostlinkscreationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK:
        case POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK:
            $fontawesome = 'fa-link';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popLocationpostlinkscreationNavigationRouteTitle', 10, 2);
function popLocationpostlinkscreationNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK => TranslationAPIFacade::getInstance()->__('Add Location Post Link', 'pop-locationpostlinkscreation'),
        POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK => TranslationAPIFacade::getInstance()->__('Edit Location Post Link', 'pop-locationpostlinkscreation'),
    ];
    return $titles[$route] ?? $title;
}
