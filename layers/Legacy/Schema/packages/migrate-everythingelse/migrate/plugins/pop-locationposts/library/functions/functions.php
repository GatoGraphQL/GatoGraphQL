<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

\PoP\Root\App::addFilter('gd_postname', 'locationpostsPostname', 10, 2);
function locationpostsPostname($name, $post_id = null)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
        return PoP_LocationPosts_PostNameUtils::getNameUc();
    }

    return $name;
}
\PoP\Root\App::addFilter('gd_format_postname', 'locationpostsFormatPostname', 10, 3);
function locationpostsFormatPostname($name, $post_id, $format)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
        if ($format == 'lc') {
            return PoP_LocationPosts_PostNameUtils::getNameLc();
        } elseif ($format == 'plural-lc') {
            return PoP_LocationPosts_PostNameUtils::getNameLc();
        }
    }

    return $name;
}
\PoP\Root\App::addFilter('gd_posticon', 'locationpostsPosticon', 10, 2);
function locationpostsPosticon($icon, $post_id = null)
{
    if (defined('POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS') && POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS) {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_LOCATIONPOSTS_POSTTYPE_LOCATIONPOST) {
            return getRouteIcon(POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS, false);
        }
    }

    return $icon;
}
