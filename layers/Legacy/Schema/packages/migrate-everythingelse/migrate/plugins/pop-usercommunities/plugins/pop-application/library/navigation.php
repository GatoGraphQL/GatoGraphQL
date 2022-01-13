<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

/**
 * navigation.php
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popUsercommunitiesRouteIcon', 10, 3);
function popUsercommunitiesRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_USERCOMMUNITIES_ROUTE_MEMBERS:
        case POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS:
        case POP_USERCOMMUNITIES_ROUTE_MYMEMBERS:
            $fontawesome = 'fa-users';
            break;

        case POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS:
            $fontawesome = 'fa-user-plus';
            break;

        case POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP:
            $fontawesome = 'fa-certificate';
            break;

        case POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES:
        case POP_USERCOMMUNITIES_ROUTE_COMMUNITIES:
            $fontawesome = 'fa-user-circle';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popUsercommunitiesNavigationRouteTitle', 10, 2);
function popUsercommunitiesNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_USERCOMMUNITIES_ROUTE_MEMBERS => TranslationAPIFacade::getInstance()->__('Members', 'pop-usercommunities'),
        POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS => TranslationAPIFacade::getInstance()->__('Community plus members', 'pop-usercommunities'),
        POP_USERCOMMUNITIES_ROUTE_MYMEMBERS => TranslationAPIFacade::getInstance()->__('My Members', 'pop-usercommunities'),
        POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS => TranslationAPIFacade::getInstance()->__('Invite new members', 'pop-usercommunities'),
        POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP => TranslationAPIFacade::getInstance()->__('Edit Membership', 'pop-usercommunities'),
        POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES => TranslationAPIFacade::getInstance()->__('My Communities', 'pop-usercommunities'),
        POP_USERCOMMUNITIES_ROUTE_COMMUNITIES => TranslationAPIFacade::getInstance()->__('Communities', 'pop-usercommunities'),
    ];
    return $titles[$route] ?? $title;
}
