<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popRelatedpostsRouteIcon', 10, 3);
function popRelatedpostsRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT:
            $fontawesome = 'fa-asterisk';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popRelatedpostsNavigationRouteTitle', 10, 2);
function popRelatedpostsNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT => TranslationAPIFacade::getInstance()->__('Related Content', 'pop-relatedposts'),
    ];
    return $titles[$route] ?? $title;
}
