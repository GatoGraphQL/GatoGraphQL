<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\PostTags\ComponentConfiguration as PostTagsComponentConfiguration;
use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;

/**
 * Implementation of the icons
 */
\PoP\Root\App::getHookManager()->addFilter('route:icon', 'popblogRouteIcon', 10, 3);
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

        case UsersComponentConfiguration::getUsersRoute():
            $fontawesome = 'fa-users';
            break;

        case POP_BLOG_ROUTE_COMMENTS:
        case POP_ADDCOMMENTS_ROUTE_ADDCOMMENT:
            $fontawesome = 'fa-comments';
            break;

        case PostTagsComponentConfiguration::getPostTagsRoute():
            $fontawesome = 'fa-hashtag';
            break;

        case POP_USERPLATFORM_ROUTE_EDITPROFILE:
            $fontawesome = 'fa-pencil-square-o';
            break;

        case POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE:
            $fontawesome = 'fa-pencil-square';
            break;

        case PostsComponentConfiguration::getPostsRoute():
            $fontawesome = 'fa-circle';
            break;
    }

    return processIcon($icon, $fontawesome, $html);
}

\PoP\Root\App::getHookManager()->addFilter('route:title', 'popblogNavigationRouteTitle', 10, 2);
function popblogNavigationRouteTitle($title, $route)
{
    $titles = [
        POP_BLOG_ROUTE_SEARCHCONTENT => TranslationAPIFacade::getInstance()->__('Search content', 'pop-blog'),
        POP_BLOG_ROUTE_SEARCHUSERS => TranslationAPIFacade::getInstance()->__('Search users', 'pop-blog'),
        POP_BLOG_ROUTE_CONTENT => TranslationAPIFacade::getInstance()->__('Content', 'pop-blog'),
        UsersComponentConfiguration::getUsersRoute() => TranslationAPIFacade::getInstance()->__('Users', 'pop-blog'),
        POP_BLOG_ROUTE_COMMENTS => TranslationAPIFacade::getInstance()->__('Comments', 'pop-blog'),
        POP_ADDCOMMENTS_ROUTE_ADDCOMMENT => TranslationAPIFacade::getInstance()->__('Add Comment', 'pop-addcomments'),
        PostTagsComponentConfiguration::getPostTagsRoute() => TranslationAPIFacade::getInstance()->__('Tags', 'pop-blog'),
        POP_USERPLATFORM_ROUTE_EDITPROFILE => TranslationAPIFacade::getInstance()->__('Edit Profile', 'pop-userplatform'),
        POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE => TranslationAPIFacade::getInstance()->__('Change Password', 'pop-userplatform'),
        PostsComponentConfiguration::getPostsRoute() => TranslationAPIFacade::getInstance()->__('Posts', 'pop-application-processors'),
    ];
    return $titles[$route] ?? $title;
}
