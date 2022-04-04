<?php

class PoP_RelatedPosts_SubmenuHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:single:routes',
            $this->addSingleRoutes(...)
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
