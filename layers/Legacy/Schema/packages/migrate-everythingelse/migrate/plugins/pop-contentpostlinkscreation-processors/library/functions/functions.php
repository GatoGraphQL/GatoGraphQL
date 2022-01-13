<?php
use PoP\Engine\Route\RouteUtils;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

/**
 * createupdate-utils.php
 */
\PoP\Root\App::addFilter('gd-createupdateutils:edit-url', 'cplcCreateupdateutilsEditUrl', 100, 2);
function cplcCreateupdateutilsEditUrl($url, $post_id)
{
    if (defined('POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK') && POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK && defined('POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS') && POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS) {
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        if ($postCategoryTypeAPI->hasCategory(POP_CONTENTPOSTLINKS_CAT_CONTENTPOSTLINKS, $post_id)) {
            return RouteUtils::getRouteURL(POP_CONTENTPOSTLINKSCREATION_ROUTE_EDITCONTENTPOSTLINK);
        }
    }

    return $url;
}
