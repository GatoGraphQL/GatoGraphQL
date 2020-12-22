<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

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
        if (defined('POP_POSTS_ROUTE_POSTS') && POP_POSTS_ROUTE_POSTS) {
            $routes[POP_POSTS_ROUTE_POSTS] = HooksAPIFacade::getInstance()->applyFilters(
                'PoP_Blog_SubmenuHooks:mainsubheaders',
                array()
            );
            $vars = ApplicationState::getVars();
            $route = $vars['route'];
            if (in_array($route, $routes[POP_POSTS_ROUTE_POSTS])) {
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
