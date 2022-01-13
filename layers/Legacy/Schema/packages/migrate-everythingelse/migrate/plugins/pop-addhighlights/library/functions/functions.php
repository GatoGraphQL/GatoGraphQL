<?php

use PoP\Engine\Route\RouteUtils;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

\PoP\Root\App::getHookManager()->addFilter('gd-createupdateutils:edit-url', 'maybeGetHighlightEditUrl', 10, 2);
function maybeGetHighlightEditUrl($url, $post_id)
{
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT) {
        return RouteUtils::getRouteURL(\POP_ADDHIGHLIGHTS_ROUTE_EDITHIGHLIGHT);
    }

    return $url;
}


\PoP\Root\App::getHookManager()->addFilter('get_title_as_basic_content:post_types', 'addHighlightsPostType');
function addHighlightsPostType($post_types)
{
    $post_types[] = \POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT;
    return $post_types;
}
