<?php
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popwassupUserstanceRouteIcon', 10, 3);
function popwassupUserstanceRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_USERSTANCE_ROUTE_MYSTANCES:
        case POP_USERSTANCE_ROUTE_ADDSTANCE:
        case POP_USERSTANCE_ROUTE_EDITSTANCE:
        case POP_USERSTANCE_ROUTE_ADDOREDITSTANCE:
        case POP_USERSTANCE_ROUTE_STANCES:
        case POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS:
        case POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS:
            $fontawesome = 'fa-commenting-o';
            break;

        case POP_USERSTANCE_ROUTE_STANCES_PRO:
        case POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL:
        case POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE:
            $fontawesome = 'fa-thumbs-o-up';
            break;

        case POP_USERSTANCE_ROUTE_STANCES_AGAINST:
        case POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL:
        case POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE:
            $fontawesome = 'fa-thumbs-o-down';
            break;

        case POP_USERSTANCE_ROUTE_STANCES_NEUTRAL:
        case POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL:
        case POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE:
            $fontawesome = 'fa-hand-peace-o';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popwassupUserstanceNavigationRouteTitle', 10, 2);
function popwassupUserstanceNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_USERSTANCE_ROUTE_MYSTANCES => TranslationAPIFacade::getInstance()->__('My Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_ADDSTANCE => TranslationAPIFacade::getInstance()->__('Add Stance', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_EDITSTANCE => TranslationAPIFacade::getInstance()->__('Edit Stance', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_ADDOREDITSTANCE => TranslationAPIFacade::getInstance()->__('Add or Edit Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES => TranslationAPIFacade::getInstance()->__('Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS => TranslationAPIFacade::getInstance()->__('Stances by Organizations', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS => TranslationAPIFacade::getInstance()->__('Stances by Individuals', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_PRO => TranslationAPIFacade::getInstance()->__('Pro Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL => TranslationAPIFacade::getInstance()->__('General Pro Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE => TranslationAPIFacade::getInstance()->__('Particular Pro Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_AGAINST => TranslationAPIFacade::getInstance()->__('Against Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL => TranslationAPIFacade::getInstance()->__('General Against Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE => TranslationAPIFacade::getInstance()->__('Particular Against Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_NEUTRAL => TranslationAPIFacade::getInstance()->__('Neutral Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL => TranslationAPIFacade::getInstance()->__('General Neutral Stances', 'pop-userstance'),
        POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE => TranslationAPIFacade::getInstance()->__('Particular Neutral Stances', 'pop-userstance'),
    ];
    return $titles[$route] ?? $title;
}
