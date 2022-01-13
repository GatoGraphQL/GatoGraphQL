<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'aalpopRouteIcon', 10, 3);
function aalpopRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS:
            $fontawesome = 'fa-bell-o';
            break;

        case POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD:
        case POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD:
            $fontawesome = 'fa-circle-o';
            break;

        case POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD:
            $fontawesome = 'fa-circle';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'aalpopNavigationRouteTitle', 10, 2);
function aalpopNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS => TranslationAPIFacade::getInstance()->__('Notifications', 'pop-notifications'),
        POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKALLASREAD => TranslationAPIFacade::getInstance()->__('Mark all as read', 'pop-notifications'),
        POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASREAD => TranslationAPIFacade::getInstance()->__('Mark as read', 'pop-notifications'),
        POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS_MARKASUNREAD => TranslationAPIFacade::getInstance()->__('Mark as unread', 'pop-notifications'),
    ];
    return $titles[$route] ?? $title;
}
