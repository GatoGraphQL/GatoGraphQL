<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'getpopdemoCppRouteIcon', 10, 3);
function getpopdemoCppRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_ARTICLES:
            $fontawesome = 'fa-comment';
            break;

        case GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_ANNOUNCEMENTS:
            $fontawesome = 'fa-bullhorn';
            break;

        case GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_RESOURCES:
            $fontawesome = 'fa-book';
            break;

        case GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_BLOG:
            $fontawesome = 'fa-pencil-square';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'getpopdemoCppNavigationRouteTitle', 10, 2);
function getpopdemoCppNavigationRouteTitle($title, $route)
{
    $titles = [
        GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_ARTICLES => TranslationAPIFacade::getInstance()->__('Articles', 'getpop-demo-pages'),
        GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_ANNOUNCEMENTS => TranslationAPIFacade::getInstance()->__('Announcements', 'getpop-demo-pages'),
        GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_RESOURCES => TranslationAPIFacade::getInstance()->__('Resources', 'getpop-demo-pages'),
        GETPOPDEMO_PAGES_ROUTEPLACEHOLDER_BLOG => TranslationAPIFacade::getInstance()->__('Blog', 'getpop-demo-pages'),
    ];
    return $titles[$route] ?? $title;
}
