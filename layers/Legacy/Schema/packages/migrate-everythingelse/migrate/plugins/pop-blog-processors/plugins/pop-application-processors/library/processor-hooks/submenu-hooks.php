<?php
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;

class PoP_Blog_SubmenuHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:author:routes',
            $this->addRoutes(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:tag:routes',
            $this->addRoutes(...)
        );
    }

    public function addRoutes($routes)
    {

        // Events
        if (defined('PostsModuleConfiguration::getPostsRoute()') && PostsModuleConfiguration::getPostsRoute()) {
            $routes[PostsModuleConfiguration::getPostsRoute()] = \PoP\Root\App::applyFilters(
                'PoP_Blog_SubmenuHooks:mainsubheaders',
                array()
            );
            $route = \PoP\Root\App::getState('route');
            if (in_array($route, $routes[PostsModuleConfiguration::getPostsRoute()])) {
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
