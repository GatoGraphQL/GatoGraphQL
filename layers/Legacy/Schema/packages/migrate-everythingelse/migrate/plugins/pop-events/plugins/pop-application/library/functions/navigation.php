<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popEventsRouteIcon', 10, 3);
function popEventsRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_EVENTS_ROUTE_EVENTS:
        case POP_EVENTS_ROUTE_PASTEVENTS:
            $fontawesome = 'fa-calendar';
            break;

        case POP_EVENTS_ROUTE_EVENTSCALENDAR:
            $fontawesome = 'fa-calendar-o';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popEventsNavigationRouteTitle', 10, 2);
function popEventsNavigationRouteTitle($title, $route)
{   
    $titles = [
        POP_EVENTS_ROUTE_EVENTS => TranslationAPIFacade::getInstance()->__('Events', 'pop-events'),
        POP_EVENTS_ROUTE_PASTEVENTS => TranslationAPIFacade::getInstance()->__('Past Events', 'pop-events'),
        POP_EVENTS_ROUTE_EVENTSCALENDAR => TranslationAPIFacade::getInstance()->__('Event Calendar', 'pop-events'),
    ];
    return $titles[$route] ?? $title;
}
