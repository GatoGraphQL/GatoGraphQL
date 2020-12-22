<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

HooksAPIFacade::getInstance()->addFilter('gd_postname', 'blogPostname', 10, 3);
function blogPostname($name, $post_id, $format)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $cats = PoP_CategoryPosts_Utils::getCats();
        $categoryapi = \PoPSchema\Categories\FunctionAPIFactory::getInstance();
        $post_cats = $categoryapi->getCustomPostCategories($post_id, ['return-type' => ReturnTypes::IDS]);
        if ($intersected = array_values(array_intersect($cats, $post_cats))) {
            return gdGetCategoryname($intersected[0], $format);
        }
    }

    return $name;
}


HooksAPIFacade::getInstance()->addFilter('gd_posticon', 'blogPosticon', 10, 2);
function blogPosticon($icon, $post_id)
{
    $postTypeAPI = PostTypeAPIFacade::getInstance();
    $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
    if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
        $cats = PoP_CategoryPosts_Utils::getCats();
        $categoryapi = \PoPSchema\Categories\FunctionAPIFactory::getInstance();
        $post_cats = $categoryapi->getCustomPostCategories($post_id, ['return-type' => ReturnTypes::IDS]);
        if ($intersected = array_values(array_intersect($cats, $post_cats))) {
            $cat_routes = PoP_CategoryPosts_Utils::getCatRoutes();
            return getRouteIcon($cat_routes[$intersected[0]], false);
        }
    }

    return $icon;
}
