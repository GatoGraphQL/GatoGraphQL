<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_CategoryPostsProcessors_Blog_SubmenuHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Blog_SubmenuHooks:mainsubheaders',
            array($this, 'addMainsubheaders')
        );
    }

    public function addMainsubheaders($routes)
    {
        if ($cat_routes = array_values(PoP_CategoryPosts_Utils::getCatRoutes())) {
            return array_merge(
                $routes,
                $cat_routes
            );
        }
        
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_CategoryPostsProcessors_Blog_SubmenuHooks();
