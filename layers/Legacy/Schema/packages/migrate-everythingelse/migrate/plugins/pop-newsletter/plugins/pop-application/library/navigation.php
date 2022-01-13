<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'genericformsRouteIcon', 10, 3);
function genericformsRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_NEWSLETTER_ROUTE_NEWSLETTER:
        case POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION:
            $fontawesome = 'fa-envelope';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'genericformsNavigationRouteTitle', 10, 2);
function genericformsNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_NEWSLETTER_ROUTE_NEWSLETTER => TranslationAPIFacade::getInstance()->__('Subscribe to our Newsletter', 'pop-newsletter'),
        POP_NEWSLETTER_ROUTE_NEWSLETTERUNSUBSCRIPTION => TranslationAPIFacade::getInstance()->__('Unsubscribe', 'pop-newsletter'),
    ];
    return $titles[$route] ?? $title;
}
