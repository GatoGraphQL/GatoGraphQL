<?php
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

\PoP\Root\App::addFilter('gd_postname', 'nosearchcategorypostsPostname', 10, 3);
function nosearchcategorypostsPostname($name, $post_id, $format)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $cats = PoP_NoSearchCategoryPosts_Utils::getCats();
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $post_cats = $postCategoryTypeAPI->getCustomPostCategories($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        if ($intersected = array_values(array_intersect($cats, $post_cats))) {
            return gdGetCategoryname($intersected[0], $format);
        }
    }

    return $name;
}


\PoP\Root\App::addFilter('gd_posticon', 'nosearchcategorypostsPosticon', 10, 2);
function nosearchcategorypostsPosticon($icon, $post_id)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $cats = PoP_NoSearchCategoryPosts_Utils::getCats();
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $post_cats = $postCategoryTypeAPI->getCustomPostCategories($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        if ($intersected = array_values(array_intersect($cats, $post_cats))) {
            $cat_routes = PoP_NoSearchCategoryPosts_Utils::getCatRoutes();
            return getRouteIcon($cat_routes[$intersected[0]], false);
        }
    }

    return $icon;
}
