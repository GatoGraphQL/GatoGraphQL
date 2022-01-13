<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

HooksAPIFacade::getInstance()->addFilter('route:icon', 'popcoreRouteIcon', 10, 3);
function popcoreRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_SOCIALNETWORK_ROUTE_FOLLOWERS:
            $fontawesome = 'fa-users';
            break;

        case POP_SOCIALNETWORK_ROUTE_FOLLOWUSER:
        case POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER:
        case POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS:
        case POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS:
        case POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO:
        case POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG:
        case POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG:
            $fontawesome = 'fa-hand-o-right';
            break;

        case POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST:
        case POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST:
        case POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS:
        case POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY:
            // $fontawesome = 'fa-hand-o-right';
            $fontawesome = 'fa-thumbs-o-up';
            break;

        case POP_SOCIALNETWORK_ROUTE_UPVOTEPOST:
        case POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST:
        case POP_SOCIALNETWORK_ROUTE_UPVOTEDBY:
            $fontawesome = 'fa-thumbs-up';
            break;

        case POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST:
        case POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST:
        case POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY:
            $fontawesome = 'fa-thumbs-down';
            break;

        case POP_USERPLATFORM_ROUTE_SETTINGS:
            $fontawesome = 'fa-gear';
            break;

        case POP_USERPLATFORM_ROUTE_MYPROFILE:
            $fontawesome = 'fa-user';
            break;

        case POP_USERPLATFORM_ROUTE_MYPREFERENCES:
            $fontawesome = 'fa-cog';
            break;

        case POP_USERPLATFORM_ROUTE_INVITENEWUSERS:
            $fontawesome = 'fa-user-plus';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popcoreNavigationRouteTitle', 10, 2);
function popcoreNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_SOCIALNETWORK_ROUTE_FOLLOWERS => TranslationAPIFacade::getInstance()->__('Followers', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_FOLLOWUSER => TranslationAPIFacade::getInstance()->__('Follow', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UNFOLLOWUSER => TranslationAPIFacade::getInstance()->__('Unfollow', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS => TranslationAPIFacade::getInstance()->__('Following', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS => TranslationAPIFacade::getInstance()->__('Subscribers', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO => TranslationAPIFacade::getInstance()->__('Subscribed to', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_SUBSCRIBETOTAG => TranslationAPIFacade::getInstance()->__('Subscribe', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UNSUBSCRIBEFROMTAG => TranslationAPIFacade::getInstance()->__('Unsubscribe', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST => TranslationAPIFacade::getInstance()->__('Recommend', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST => TranslationAPIFacade::getInstance()->__('Unrecommend', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS => TranslationAPIFacade::getInstance()->__('Recommended', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY => TranslationAPIFacade::getInstance()->__('Recommended by', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UPVOTEPOST => TranslationAPIFacade::getInstance()->__('Upvote', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST => TranslationAPIFacade::getInstance()->__('Undo upvote', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UPVOTEDBY => TranslationAPIFacade::getInstance()->__('Upvoted by', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST => TranslationAPIFacade::getInstance()->__('Downvote', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST => TranslationAPIFacade::getInstance()->__('Undo downvote', 'pop-socialnetwork'),
        POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY => TranslationAPIFacade::getInstance()->__('Downvoted by', 'pop-socialnetwork'),
        POP_USERPLATFORM_ROUTE_SETTINGS => TranslationAPIFacade::getInstance()->__('Settings', 'pop-userplatform'),
        POP_USERPLATFORM_ROUTE_MYPROFILE => TranslationAPIFacade::getInstance()->__('My Profile', 'pop-userplatform'),
        POP_USERPLATFORM_ROUTE_MYPREFERENCES => TranslationAPIFacade::getInstance()->__('My Preferences', 'pop-userplatform'),
        POP_USERPLATFORM_ROUTE_INVITENEWUSERS => TranslationAPIFacade::getInstance()->__('Invite friends', 'pop-userplatform'),
    ];
    return $titles[$route] ?? $title;
}
