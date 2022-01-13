<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popContentpostlinkscreationRouteIcon', 10, 3);
function popContentpostlinkscreationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS:
        case POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK:
        case POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK:
            $fontawesome = 'fa-link';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popContentpostlinkscreationNavigationRouteTitle', 10, 2);
function popContentpostlinkscreationNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_CONTENTPOSTLINKSCREATION_ROUTE_MYCONTENTPOSTLINKS => TranslationAPIFacade::getInstance()->__('My Post Links', 'pop-contentpostlinkscreation'),
        POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK => TranslationAPIFacade::getInstance()->__('Add Post Link', 'pop-contentpostlinkscreation'),
        POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK => TranslationAPIFacade::getInstance()->__('Edit Post Link', 'pop-contentpostlinkscreation'),
    ];
    return $titles[$route] ?? $title;
}
