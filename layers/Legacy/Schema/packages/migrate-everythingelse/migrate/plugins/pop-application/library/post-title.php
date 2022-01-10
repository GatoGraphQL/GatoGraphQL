<?php

use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('popcms:post:title', 'maybeGetTitleAsBasicContent', 10, 2);
function maybeGetTitleAsBasicContent($title, $post_id = null)
{
    $post_types = HooksAPIFacade::getInstance()->applyFilters(
        'get_title_as_basic_content:post_types',
        array()
    );
    if (is_null($post_id)) {
        $vars = ApplicationState::getVars();
        $post_id = $vars['routing']['queried-object-id'];
    }
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if (in_array($customPostTypeAPI->getCustomPostType($post_id), $post_types)) {
        return limitString($customPostTypeAPI->getPlainTextContent($post_id), 100);
    }

    return $title;
}
