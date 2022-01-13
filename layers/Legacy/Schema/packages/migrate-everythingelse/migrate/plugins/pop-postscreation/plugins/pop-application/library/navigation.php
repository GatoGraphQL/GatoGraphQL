<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popPostscreationRouteIcon', 10, 3);
function popPostscreationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_POSTSCREATION_ROUTE_MYPOSTS:
        case POP_POSTSCREATION_ROUTE_ADDPOST:
        case POP_POSTSCREATION_ROUTE_EDITPOST:
            $fontawesome = 'fa-circle';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popPostscreationNavigationTitle', 10, 2);
function popPostscreationNavigationTitle($title, $route)
{
    $titles = [
        POP_POSTSCREATION_ROUTE_MYPOSTS => TranslationAPIFacade::getInstance()->__('My Posts', 'pop-postscreation'),
        POP_POSTSCREATION_ROUTE_ADDPOST => TranslationAPIFacade::getInstance()->__('Add Post', 'pop-postscreation'),
        POP_POSTSCREATION_ROUTE_EDITPOST => TranslationAPIFacade::getInstance()->__('Edit Post', 'pop-postscreation'),
    ];    
    return $titles[$route] ?? $title;
}
