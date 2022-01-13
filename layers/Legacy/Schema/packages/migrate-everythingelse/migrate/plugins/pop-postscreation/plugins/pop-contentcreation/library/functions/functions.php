<?php
use PoP\Engine\Route\RouteUtils;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;

\PoP\Root\App::addFilter('gd-createupdateutils:edit-url', 'popPostscreationCreateupdateutilsEditUrl', 0, 2);
function popPostscreationCreateupdateutilsEditUrl($url, $post_id)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        return RouteUtils::getRouteURL(POP_POSTSCREATION_ROUTE_EDITPOST);
    }

    return $url;
}
