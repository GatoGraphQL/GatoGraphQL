<?php
use PoP\Engine\Route\RouteUtils;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

\PoP\Root\App::addFilter('gd_postname', 'userstancePostname', 10, 2);
function userstancePostname($name, $post_id = null)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
        return PoP_UserStance_PostNameUtils::getNameUc();
    }

    return $name;
}
\PoP\Root\App::addFilter('gd_format_postname', 'userstanceFormatPostname', 10, 3);
function userstanceFormatPostname($name, $post_id, $format)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
        if ($format == 'lc') {
            return PoP_UserStance_PostNameUtils::getNameLc();
        } elseif ($format == 'plural-lc') {
            return PoP_UserStance_PostNameUtils::getNamesLc();
        }
    }

    return $name;
}
\PoP\Root\App::addFilter('gd_posticon', 'userstancePosticon', 10, 2);
function userstancePosticon($icon, $post_id = null)
{
    if (defined('POP_USERSTANCE_ROUTE_STANCES') && POP_USERSTANCE_ROUTE_STANCES) {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
            return getRouteIcon(POP_USERSTANCE_ROUTE_STANCES, false);
        }
    }

    return $icon;
}

// \PoP\Root\App::addFilter('gdPostParentpageid', 'userstancePostParentpageid', 10, 2);
// function userstancePostParentpageid($pageid, $post_id)
// {
//     if (defined('POP_USERSTANCE_ROUTE_STANCES') && POP_USERSTANCE_ROUTE_STANCES) {
//         $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
//         if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
//             return POP_USERSTANCE_ROUTE_STANCES;
//         }
//     }

//     return $pageid;
// }

\PoP\Root\App::addFilter('gd-createupdateutils:edit-url', 'userstanceCreateupdateutilsEditUrl', 10, 2);
function userstanceCreateupdateutilsEditUrl($url, $post_id)
{
    if (defined('POP_USERSTANCE_ROUTE_EDITSTANCE') && POP_USERSTANCE_ROUTE_EDITSTANCE) {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        if ($customPostTypeAPI->getCustomPostType($post_id) == POP_USERSTANCE_POSTTYPE_USERSTANCE) {
            return RouteUtils::getRouteURL(POP_USERSTANCE_ROUTE_EDITSTANCE);
        }
    }

    return $url;
}

\PoP\Root\App::addFilter('get_title_as_basic_content:post_types', 'addUserstancePostType');
function addUserstancePostType($post_types)
{
    $post_types[] = POP_USERSTANCE_POSTTYPE_USERSTANCE;
    return $post_types;
}
