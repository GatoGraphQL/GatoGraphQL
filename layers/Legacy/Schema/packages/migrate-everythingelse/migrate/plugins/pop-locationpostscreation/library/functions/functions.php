<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd-createupdateutils:edit-url', 'locationpostsCreateupdateutilsEditUrl', 10, 2);
function locationpostsCreateupdateutilsEditUrl($url, $post_id)
{
    if (defined('POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST') && POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST) {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
            return RouteUtils::getRouteURL(POP_LOCATIONPOSTSCREATION_ROUTE_EDITLOCATIONPOST);
        }
    }

    return $url;
}
