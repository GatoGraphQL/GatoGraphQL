<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_RelatedPosts_SubmenuHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Module_Processor_CustomSubMenus:single:routes',
            array($this, 'addSingleRoutes')
        );
    }

    public function addSingleRoutes($routes)
    {
        $routes[POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT] = array();
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_RelatedPosts_SubmenuHooks();
