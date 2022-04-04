<?php
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPCMSSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

\PoP\Root\App::addFilter('gd_postname', blogPostname(...), 10, 3);
function blogPostname($name, $post_id, $format)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $cats = PoP_CategoryPosts_Utils::getCats();
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $post_cats = $postCategoryTypeAPI->getCustomPostCategories($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        if ($intersected = array_values(array_intersect($cats, $post_cats))) {
            return gdGetCategoryname($intersected[0], $format);
        }
    }

    return $name;
}


\PoP\Root\App::addFilter('gd_posticon', blogPosticon(...), 10, 2);
function blogPosticon($icon, $post_id)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $cats = PoP_CategoryPosts_Utils::getCats();
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $post_cats = $postCategoryTypeAPI->getCustomPostCategories($post_id, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        if ($intersected = array_values(array_intersect($cats, $post_cats))) {
            $cat_routes = PoP_CategoryPosts_Utils::getCatRoutes();
            return getRouteIcon($cat_routes[$intersected[0]], false);
        }
    }

    return $icon;
}
