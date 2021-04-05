<?php
// use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
// use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

// HooksAPIFacade::getInstance()->addFilter('gdPostParentpageid', 'categorypostsPostParentpageid', 10, 2);
// function categorypostsPostParentpageid($pageid, $post_id)
// {
    // $postTypeAPI = PostTypeAPIFacade::getInstance();
    // $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
//     if ($customPostTypeAPI->getCustomPostType($post_id) == $postTypeAPI->getPostCustomPostType()) {
//         $cats = PoP_CategoryPosts_Utils::getCats();
//         $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
//         $post_cats = $postCategoryTypeAPI->getCustomPostCategories($post_id, ['return-type' => ReturnTypes::IDS]);
//         if ($intersected = array_values(array_intersect($cats, $post_cats))) {
//             $cat_routes = PoP_CategoryPosts_Utils::getCatRoutes();
//             return $cat_routes[$intersected[0]];
//         }
//     }

//     return $pageid;
// }
