<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:multilayout_labels', 'categorypostsMultilayoutLabels');
function categorypostsMultilayoutLabels($labels)
{
    $label = '<span class="label label-%s">%s</span>';
    $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
    foreach (PoP_CategoryPosts_Utils::getCatRoutes() as $cat => $route) {
        $labels['post-'.$cat] = sprintf(
            $label,
            $postCategoryTypeAPI->getCategorySlug($cat),
            getRouteIcon($route, true).gdGetCategoryname($cat)
        );
    }

    return $labels;
}
