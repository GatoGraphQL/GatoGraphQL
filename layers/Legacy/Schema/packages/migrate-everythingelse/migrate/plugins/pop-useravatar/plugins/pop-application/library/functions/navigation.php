<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */

/**
 * Implementation of the icons
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popuseravatarRouteIcon', 10, 3);
function popuseravatarRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_USERAVATAR_ROUTE_EDITAVATAR:
            $fontawesome = 'fa-camera';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popuseravatarNavigationRouteTitle', 10, 2);
function popuseravatarNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_USERAVATAR_ROUTE_EDITAVATAR => TranslationAPIFacade::getInstance()->__('Edit Avatar', 'pop-useravatar'),
    ];
    return $titles[$route] ?? $title;
}
