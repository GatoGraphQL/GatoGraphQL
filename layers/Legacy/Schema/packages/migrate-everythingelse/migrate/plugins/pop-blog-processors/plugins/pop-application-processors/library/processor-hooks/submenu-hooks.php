<?php
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;

class PoP_Blog_SubmenuHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:author:routes',
            array($this, 'addRoutes')
        );
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:tag:routes',
            array($this, 'addRoutes')
        );
    }

    public function addRoutes($routes)
    {

        // Events
        if (defined('PostsComponentConfiguration::getPostsRoute()') && PostsComponentConfiguration::getPostsRoute()) {
            $routes[PostsComponentConfiguration::getPostsRoute()] = HooksAPIFacade::getInstance()->applyFilters(
                'PoP_Blog_SubmenuHooks:mainsubheaders',
                array()
            );
            $route = \PoP\Root\App::getState('route');
            if (in_array($route, $routes[PostsComponentConfiguration::getPostsRoute()])) {
                $routes[$route] = array();
            }
        }

        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_Blog_SubmenuHooks();
