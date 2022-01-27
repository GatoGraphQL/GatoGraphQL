<?php

class PoP_CategoryPostsProcessors_Blog_SubmenuHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
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
