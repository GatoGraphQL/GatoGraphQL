<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::addFilter('route:icon', 'popEventscreationRouteIcon', 10, 3);
function popEventscreationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_EVENTSCREATION_ROUTE_MYEVENTS:
        case POP_EVENTSCREATION_ROUTE_ADDEVENT:
        case POP_EVENTSCREATION_ROUTE_EDITEVENT:
        case POP_EVENTSCREATION_ROUTE_MYPASTEVENTS:
            $fontawesome = 'fa-calendar';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::addFilter('route:title', 'popEventscreationNavigationRouteTitle', 10, 2);
function popEventscreationNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_EVENTSCREATION_ROUTE_MYEVENTS => TranslationAPIFacade::getInstance()->__('My Events', 'pop-eventscreation'),
        POP_EVENTSCREATION_ROUTE_ADDEVENT => TranslationAPIFacade::getInstance()->__('Add Event', 'pop-eventscreation'),
        POP_EVENTSCREATION_ROUTE_EDITEVENT => TranslationAPIFacade::getInstance()->__('Edit Event', 'pop-eventscreation'),
        POP_EVENTSCREATION_ROUTE_MYPASTEVENTS => TranslationAPIFacade::getInstance()->__('My Past Events', 'pop-eventscreation'),
    ];
    return $titles[$route] ?? $title;
}
