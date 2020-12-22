<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Implementation of the icons
 */
HooksAPIFacade::getInstance()->addFilter('route:icon', 'popblogRouteIcon', 10, 3);
function popblogRouteIcon($icon, $route, $html = true)
{
    switch ($route) {
        case POP_BLOG_ROUTE_SEARCHCONTENT:
        case POP_BLOG_ROUTE_SEARCHUSERS:
            $fontawesome = 'fa-search';
            break;

        case POP_BLOG_ROUTE_CONTENT:
            $fontawesome = 'fa-asterisk';
            break;

        case POP_USERS_ROUTE_USERS:
            $fontawesome = 'fa-users';
            break;

        case POP_BLOG_ROUTE_COMMENTS:
        case POP_ADDCOMMENTS_ROUTE_ADDCOMMENT:
            $fontawesome = 'fa-comments';
            break;

        case POP_POSTTAGS_ROUTE_POSTTAGS:
            $fontawesome = 'fa-hashtag';
            break;

        case POP_USERPLATFORM_ROUTE_EDITPROFILE:
            $fontawesome = 'fa-pencil-square-o';
            break;

        case POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE:
            $fontawesome = 'fa-pencil-square';
            break;

        case POP_POSTS_ROUTE_POSTS:
            $fontawesome = 'fa-circle';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

HooksAPIFacade::getInstance()->addFilter('route:title', 'popblogNavigationRouteTitle', 10, 2);
function popblogNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_BLOG_ROUTE_SEARCHCONTENT => TranslationAPIFacade::getInstance()->__('Search content', 'pop-blog'),
        POP_BLOG_ROUTE_SEARCHUSERS => TranslationAPIFacade::getInstance()->__('Search users', 'pop-blog'),
        POP_BLOG_ROUTE_CONTENT => TranslationAPIFacade::getInstance()->__('Content', 'pop-blog'),
        POP_USERS_ROUTE_USERS => TranslationAPIFacade::getInstance()->__('Users', 'pop-blog'),
        POP_BLOG_ROUTE_COMMENTS => TranslationAPIFacade::getInstance()->__('Comments', 'pop-blog'),
        POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => TranslationAPIFacade::getInstance()->__('Add Comment', 'pop-addcomments'),
        POP_POSTTAGS_ROUTE_POSTTAGS => TranslationAPIFacade::getInstance()->__('Tags', 'pop-blog'),
        POP_USERPLATFORM_ROUTE_EDITPROFILE => TranslationAPIFacade::getInstance()->__('Edit Profile', 'pop-userplatform'),
        POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => TranslationAPIFacade::getInstance()->__('Change Password', 'pop-userplatform'),
        POP_POSTS_ROUTE_POSTS => TranslationAPIFacade::getInstance()->__('Posts', 'pop-application-processors'),
    ];
    return $titles[$route] ?? $title;
}
