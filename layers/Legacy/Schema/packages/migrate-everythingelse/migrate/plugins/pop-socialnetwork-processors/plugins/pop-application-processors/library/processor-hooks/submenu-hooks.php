<?php

class PoP_SocialNetwork_SubmenuHooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:author:mainsubheaders',
            array($this, 'addAuthorMainsubheaders')
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:tag:mainsubheaders',
            array($this, 'addTagMainsubheaders')
        );
        \PoP\Root\App::addFilter(
            'PoP_Module_Processor_CustomSubMenus:single:routes',
            array($this, 'addSingleRoutes')
        );
    }

    public function addAuthorMainsubheaders($routes)
    {
        return array_merge(
            $routes,
            array_filter(
                array(
                    POP_SOCIALNETWORK_ROUTE_RECOMMENDEDPOSTS,
                    POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
                    POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
                    POP_SOCIALNETWORK_ROUTE_SUBSCRIBEDTO,
                )
            )
        );
    }

    public function addTagMainsubheaders($routes)
    {
        return array_merge(
            $routes,
            array_filter(
                array(
                    POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS,
                )
            )
        );
    }

    public function addSingleRoutes($routes)
    {
        $routes[POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY] = array();
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_SocialNetwork_SubmenuHooks();
