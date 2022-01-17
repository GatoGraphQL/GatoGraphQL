<?php
// use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

// \PoP\Root\App::addFilter('pop_modulemanager:multilayout_labels', 'nosearchcategorypostsMultilayoutLabels');
// function nosearchcategorypostsMultilayoutLabels($labels) {

//     $label = '<span class="label label-%s">%s</span>';

//     $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
//     foreach (PoP_NoSearchCategoryPosts_Utils::getCatRoutes() as $cat => $route) {

//         $category = get_category($cat);
//         $labels['post-'.$cat] = sprintf(
//             $label,
//             $postCategoryTypeAPI->getCategorySlug($cat),
//             getRouteIcon($route, true).gdGetCategoryname($cat)
//         );
//     }

//     return $labels;
// }
