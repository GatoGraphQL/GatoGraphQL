<?php

use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

\PoP\Root\App::addFilter(
    'the_title',// Must add a loose contract instead: 'popcms:post:title'
    'maybeGetTitleAsBasicContent',
    10,
    2
);
function maybeGetTitleAsBasicContent($title, $post_id = null)
{
    $post_types = \PoP\Root\App::applyFilters(
        'get_title_as_basic_content:post_types',
        array()
    );
    if (is_null($post_id)) {
        $post_id = \PoP\Root\App::getState(['routing', 'queried-object-id']);
    }
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if (in_array($customPostTypeAPI->getCustomPostType($post_id), $post_types)) {
        return limitString($customPostTypeAPI->getPlainTextContent($post_id), 100);
    }

    return $title;
}
