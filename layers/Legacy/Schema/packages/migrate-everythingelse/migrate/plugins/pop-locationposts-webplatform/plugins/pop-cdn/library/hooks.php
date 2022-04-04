<?php

 
class PoP_CommonPages_EM_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            $this->getThumbprintPartialpaths(...),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        if ($thumbprint == POP_CDN_THUMBPRINT_POST) {
                $routes = array_filter(
                array(
                    POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
                )
            );
        } elseif ($thumbprint == POP_CDN_THUMBPRINT_USER) {
            $routes = array_filter(
                array(
                    POP_LOCATIONPOSTS_ROUTE_LOCATIONPOSTS,
                )
            );
        }
        if ($routes) {
            foreach ($routes as $route) {
                $paths[] = $route.'/';
            }
        }
        
        return $paths;
    }
}

/**
 * Initialize
 */
new PoP_CommonPages_EM_CDN_Hooks();
