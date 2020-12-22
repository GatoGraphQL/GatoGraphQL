<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popVolunteeringRouteIcon', 10, 3);
function popVolunteeringRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_VOLUNTEERING_ROUTE_VOLUNTEER:
            $fontawesome = 'fa-leaf';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popVolunteeringNavigationRouteTitle', 10, 2);
function popVolunteeringNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_VOLUNTEERING_ROUTE_VOLUNTEER => TranslationAPIFacade::getInstance()->__('Volunteer', 'pop-volunteering'),
    ];
    return $titles[$route] ?? $title;
}
