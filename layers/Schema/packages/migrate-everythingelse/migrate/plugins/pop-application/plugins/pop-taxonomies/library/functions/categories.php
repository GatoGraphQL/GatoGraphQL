<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
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
        $categoryapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
        if ($cats = $categoryapi->getCustomPostCategories($post_id, ['return-type' => ReturnTypes::IDS])) {
            // If this post has any of the categories set as main, then return the any one of them
            if ($intersected_cats = array_values(array_intersect($cats, getTheMainCategories()))) {
                return $intersected_cats[0];
            }
        }
    }

    return null;
}

