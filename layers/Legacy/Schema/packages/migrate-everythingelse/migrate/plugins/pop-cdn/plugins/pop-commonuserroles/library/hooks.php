<?php

 
class PoP_URE_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        if ($thumbprint == POP_CDN_THUMBPRINT_USER) {
            $routes = array_filter(
                array(
                    POP_COMMONUSERROLES_ROUTE_ORGANIZATIONS,
                    POP_COMMONUSERROLES_ROUTE_INDIVIDUALS,
                )
            );
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
new PoP_URE_CDN_Hooks();
