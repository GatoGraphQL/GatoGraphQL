<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('popcms:page:title', 'gdNavigationUpdateMenuItem', PHP_INT_MAX, 2);
function gdNavigationUpdateMenuItem($title, $page_id)
{
    $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
    // Do not do anything while in the back-end
    // Otherwise, this code is actually added inside the Menu Item when creating the Menu
    if (!$cmsapplicationapi->isAdminPanel()) {

        if ($icon = getPageIcon($page_id)) {
            return $icon.$title;
        }
    }

    return $title;
}
function getPageIcon($page_id, $html = true)
{
    return HooksAPIFacade::getInstance()->applyFilters('page:icon', '', $page_id, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'getRouteTitleIcon', PHP_INT_MAX, 2);
function getRouteTitleIcon($title, $route)
{
    if ($icon = getRouteIcon($route)) {
        return $icon.$title;
    }

    return $title;
}
function getRouteIcon($route, $html = true)
{
    return HooksAPIFacade::getInstance()->applyFilters('route:icon', '', $route, $html);
}

function processIcon($icon, $fontawesome, $html)
{
    // Important: do not replace the \' below for quotes, otherwise the "Share by email" and "Embed" buttons
    // don't work for pages (eg: http://m3l.localhost/become-a-featured-community/) since the fontawesome icons
    // screw up the data-header attr in the link
    if ($fontawesome) {
        if ($html) {
            return sprintf('<i class=\'fa fa-fw %s\'></i>', $fontawesome);
        }
        return $fontawesome;
    }

    return $icon;
}


/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popApplicationRouteIcon', 10, 3);
function popApplicationRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_CONTACTUS_ROUTE_CONTACTUS:
        case POP_SHARE_ROUTE_SHAREBYEMAIL:
            $fontawesome = 'fa-envelope-o';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popApplicationNavigationRouteTitle', 10, 2);
function popApplicationNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_CONTACTUS_ROUTE_CONTACTUS => TranslationAPIFacade::getInstance()->__('Contact us', 'pop-contactus'),
        POP_SHARE_ROUTE_SHAREBYEMAIL => TranslationAPIFacade::getInstance()->__('Share by email', 'pop-sharebyemail'),
    ];
    return $titles[$route] ?? $title;
}
