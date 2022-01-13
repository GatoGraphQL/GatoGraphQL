<?php
use PoP\ComponentModel\State\ApplicationState;

class PoPTheme_UserStance_SubmenuHooks
{
    public function __construct()
    {

        // Before Events Manager sets its own links
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:author:routes',
            array($this, 'addRoutes')
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:tag:routes',
            array($this, 'addRoutes')
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:single:routes',
            array($this, 'addRoutes')
        );
    }

    public function addRoutes($routes)
    {
        $route = \PoP\Root\App::getState('route');

        if (defined('POP_USERSTANCE_ROUTE_STANCES') && POP_USERSTANCE_ROUTE_STANCES) {
            $routes[POP_USERSTANCE_ROUTE_STANCES] = array(
                POP_USERSTANCE_ROUTE_STANCES_PRO,
                POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
                POP_USERSTANCE_ROUTE_STANCES_AGAINST,
            );
            if (in_array($route, $routes[POP_USERSTANCE_ROUTE_STANCES])) {
                $routes[$route] = array();
            }
        }
        
        return $routes;
    }
}

/**
 * Initialization
 */
new PoPTheme_UserStance_SubmenuHooks();
