<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::addFilter('route:icon', 'popappRouteIcon', 10, 3);
function popappRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS:
        case POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS:
        case POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT:
        case POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT:
            $fontawesome = 'fa-bullseye';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::addFilter('route:title', 'popwassupNavigationRouteTitle', 10, 2);
function popwassupNavigationRouteTitle($title, $route)
{   
    $titles = [
        POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS => TranslationAPIFacade::getInstance()->__('Highlights', 'pop-application-processors'),
        POP_ADDHIGHLIGHTS_ROUTE_MYHIGHLIGHTS => TranslationAPIFacade::getInstance()->__('My Highlights', 'pop-application-processors'),
        POP_ADDHIGHLIGHTS_ROUTE_ADDHIGHLIGHT => TranslationAPIFacade::getInstance()->__('Add Highlight', 'pop-application-processors'),
        POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT => TranslationAPIFacade::getInstance()->__('Edit Highlight', 'pop-application-processors'),
    ];
    return $titles[$route] ?? $title;
}
