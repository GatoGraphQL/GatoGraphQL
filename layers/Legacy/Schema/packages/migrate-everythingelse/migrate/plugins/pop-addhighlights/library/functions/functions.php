<?php

use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('gd-createupdateutils:edit-url', 'maybeGetHighlightEditUrl', 10, 2);
function maybeGetHighlightEditUrl($url, $post_id)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
        return RouteUtils::getRouteURL(\POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT);
    }

    return $url;
}


HooksAPIFacade::getInstance()->addFilter('get_title_as_basic_content:post_types', 'addHighlightsPostType');
function addHighlightsPostType($post_types)
{
    $post_types[] = \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    return $post_types;
}
