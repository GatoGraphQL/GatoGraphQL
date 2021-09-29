<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

function getTheMainCategories()
{
    return HooksAPIFacade::getInstance()->applyFilters('getTheMainCategories', array());
}

function getTheMainCategory($post_id)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        if ($cats = $postCategoryTypeAPI->getCustomPostCategories($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
            // If this post has any of the categories set as main, then return the any one of them
            if ($intersected_cats = array_values(array_intersect($cats, getTheMainCategories()))) {
                return $intersected_cats[0];
            }
        }
    }

    return null;
}

