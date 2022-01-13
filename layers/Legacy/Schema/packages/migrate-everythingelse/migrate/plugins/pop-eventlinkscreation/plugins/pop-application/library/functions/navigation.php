<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popEventlinkscreationRouteIcon', 10, 3);
function popEventlinkscreationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK:
        case POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK:
            $fontawesome = 'fa-link';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popEventlinkscreationNavigationRouteTitle', 10, 2);
function popEventlinkscreationNavigationRouteTitle($title, $route)
{
    switch ($route) {
        case POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK:
        case POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK:
            $fontawesome = 'fa-link';
            break;
    }

    // Important: do not replace the \' below for quotes, otherwise the "Share by email" and "Embed" buttons
    // don't work for pages (eg: http://m3l.localhost/become-a-featured-community/) since the fontawesome icons
    // screw up the data-header attr in the link
    if ($fontawesome) {
        if ($html) {
            return sprintf('<i class=\'fa fa-fw %s\'></i>', $fontawesome);
        }
        return $fontawesome;
    }
    
    $titles = [
        POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK => TranslationAPIFacade::getInstance()->__('Add Event Link', 'pop-eventlinkscreation'),
        POP_EVENTLINKSCREATION_ROUTE_EDITEVENTLINK => TranslationAPIFacade::getInstance()->__('Edit Event Link', 'pop-eventlinkscreation'),
    ];
    return $titles[$route] ?? $title;
}
