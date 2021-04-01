<?php
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter('pop_modulemanager:multilayout_labels', 'categorypostsMultilayoutLabels');
function categorypostsMultilayoutLabels($labels)
{
    $label = '<span class="label label-%s">%s</span>';
    $categoryapi = \PoPSchema\PostCategories\FunctionAPIFactory::getInstance();
    foreach (PoP_CategoryPosts_Utils::getCatRoutes() as $cat => $route) {
        $labels['post-'.$cat] = sprintf(
            $label,
            $categoryapi->getCategorySlug($cat),
            getRouteIcon($route, true).gdGetCategoryname($cat)
        );
    }

    return $labels;
}
