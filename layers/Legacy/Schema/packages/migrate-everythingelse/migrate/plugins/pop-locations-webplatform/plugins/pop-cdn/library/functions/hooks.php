<?php

 
class PoP_Locations_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        if ($thumbprint == POP_EM_CDN_THUMBPRINT_LOCATION) {
                $routes = array_filter(
                array(
                    POP_LOCATIONS_ROUTE_LOCATIONS,
                    POP_LOCATIONS_ROUTE_LOCATIONSMAP,
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
new PoP_Locations_CDN_Hooks();
