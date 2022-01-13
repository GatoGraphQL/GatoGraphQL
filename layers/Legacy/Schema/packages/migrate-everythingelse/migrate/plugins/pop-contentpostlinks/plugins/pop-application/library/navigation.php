<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popContentpostlinksRouteIcon', 10, 3);
function popContentpostlinksRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS:
            $fontawesome = 'fa-link';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popContentpostlinksNavigationRouteTitle', 10, 2);
function popContentpostlinksNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_CONTENTPOSTLINKS_ROUTE_CONTENTPOSTLINKS => TranslationAPIFacade::getInstance()->__('Post Links', 'pop-contentpostlinks'),
    ];
    return $titles[$route] ?? $title;
}
