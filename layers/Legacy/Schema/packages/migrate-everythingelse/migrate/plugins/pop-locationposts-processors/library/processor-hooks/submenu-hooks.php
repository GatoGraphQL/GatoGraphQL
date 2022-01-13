<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_CommonPages_EM_SubmenuHooks
{
    public function __construct()
    {

        // Execute before the Events, so it can add the page right next to "All Content" button
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:author:routes',
            array($this, 'addRoutes'),
            2
        );
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:tag:routes',
            array($this, 'addRoutes'),
            2
        );
    }

    public function addRoutes($routes)
    {

        // If the values for the constants were kept in false (eg: Projects not needed for TPP Debate) then don't add them
        if (defined('POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS') && POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS) {
            $routes[POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS] = array();
        }
        
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_CommonPages_EM_SubmenuHooks();
