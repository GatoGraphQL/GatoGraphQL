<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */

/**
 * Implementation of the icons
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popUserloginRouteIcon', 10, 3);
function popUserloginRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_USERLOGIN_ROUTE_LOGIN:
            $fontawesome = 'fa-sign-in';
            break;

        case POP_USERLOGIN_ROUTE_LOSTPWD:
        case POP_USERLOGIN_ROUTE_LOSTPWDRESET:
            $fontawesome = 'fa-warning';
            break;

        case POP_USERLOGIN_ROUTE_LOGOUT:
            $fontawesome = 'fa-sign-out';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popUserloginNavigationRouteTitle', 10, 2);
function popUserloginNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_USERLOGIN_ROUTE_LOGIN => TranslationAPIFacade::getInstance()->__('Login', 'pop-userlogin'),
        POP_USERLOGIN_ROUTE_LOSTPWD => TranslationAPIFacade::getInstance()->__('Lost Password', 'pop-userlogin'),
        POP_USERLOGIN_ROUTE_LOSTPWDRESET => TranslationAPIFacade::getInstance()->__('Reset Password', 'pop-userlogin'),
        POP_USERLOGIN_ROUTE_LOGOUT => TranslationAPIFacade::getInstance()->__('Logout', 'pop-userlogin'),
    ];
    return $titles[$route] ?? $title;
}
