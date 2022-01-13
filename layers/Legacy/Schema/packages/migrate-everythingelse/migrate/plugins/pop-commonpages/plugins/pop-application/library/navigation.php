<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('page:icon', 'popwassupPageIcon', 10, 3);
function popwassupPageIcon($icon, $page_id, $html = true)
{
    switch ($page_id) {
        case POP_COMMONPAGES_PAGE_ABOUT_CONTENTGUIDELINES:
            $fontawesome = 'fa-info-circle';
            break;

        case POP_COMMONPAGES_PAGE_ADDCONTENTFAQ:
        case POP_COMMONPAGES_PAGE_ACCOUNTFAQ:
            $fontawesome = 'fa-info-circle';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popwassupRouteIcon', 10, 3);
function popwassupRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_COMMONPAGES_ROUTE_ABOUT:
            $fontawesome = 'fa-info-circle';
            break;

        case POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE:
            $fontawesome = 'fa-smile-o';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popwassupWassupNavigationRouteTitle', 10, 2);
function popwassupWassupNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_COMMONPAGES_ROUTE_ABOUT => TranslationAPIFacade::getInstance()->__('About', 'pop-commonpages'),
        POP_COMMONPAGES_ROUTE_ABOUT_WHOWEARE => TranslationAPIFacade::getInstance()->__('Who we are', 'pop-commonpages'),
    ];
    return $titles[$route] ?? $title;
}
