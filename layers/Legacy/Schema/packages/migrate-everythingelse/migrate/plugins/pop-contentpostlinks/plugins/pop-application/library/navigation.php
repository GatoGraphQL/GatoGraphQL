<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popContentpostlinksRouteIcon', 10, 3);
function popContentpostlinksRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS:
            $fontawesome = 'fa-link';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popContentpostlinksNavigationRouteTitle', 10, 2);
function popContentpostlinksNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => TranslationAPIFacade::getInstance()->__('Post Links', 'pop-contentpostlinks'),
    ];
    return $titles[$route] ?? $title;
}
