<?php
use PoP\Engine\Route\RouteUtils;
use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

\PoP\Root\App::addFilter('gd-createupdateutils:edit-url', 'popLocationpostlinkscreationCreateupdateutilsEditUrl', 100, 2);
function popLocationpostlinkscreationCreateupdateutilsEditUrl($url, $post_id)
{
    if (defined('POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS') && POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS && defined('POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK') && POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK) {
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        if ($postCategoryTypeAPI->hasCategory(POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS, $post_id)) {
            return RouteUtils::getRouteURL(POP_LOCATIONPOSTLINKSCREATION_ROUTE_EDITLOCATIONPOSTLINK);
        }
    }

    return $url;
}
