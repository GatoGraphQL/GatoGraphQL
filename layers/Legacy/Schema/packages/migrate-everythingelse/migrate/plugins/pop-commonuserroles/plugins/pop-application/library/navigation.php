<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'ureRouteIcon', 10, 3);
function ureRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION:
        case POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL:
            $fontawesome = 'fa-pencil-square-o';
            break;
            
        case POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS:
        case POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION:
            $fontawesome = 'fa-institution';
            break;

        case POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL:
        case POP_COMMONUSERROLES_ROUTE_INDIVIDUALS:
            $fontawesome = 'fa-user';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'ureNavigationRouteTitle', 10, 2);
function ureNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_COMMONUSERROLES_ROUTE_EDITPROFILEORGANIZATION => TranslationAPIFacade::getInstance()->__('Edit Organization Profile', 'pop-commonuserroles'),
        POP_COMMONUSERROLES_ROUTE_EDITPROFILEINDIVIDUAL => TranslationAPIFacade::getInstance()->__('Edit Individual Profile', 'pop-commonuserroles'),
        POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS => TranslationAPIFacade::getInstance()->__('Organizations', 'pop-commonuserroles'),
        POP_COMMONUSERROLES_ROUTE_ADDPROFILEORGANIZATION => TranslationAPIFacade::getInstance()->__('Add Organization Profile', 'pop-commonuserroles'),
        POP_COMMONUSERROLES_ROUTE_ADDPROFILEINDIVIDUAL => TranslationAPIFacade::getInstance()->__('Add Individual Profile', 'pop-commonuserroles'),
        POP_COMMONUSERROLES_ROUTE_INDIVIDUALS => TranslationAPIFacade::getInstance()->__('Individuals', 'pop-commonuserroles'),
    ];
    return $titles[$route] ?? $title;
}
