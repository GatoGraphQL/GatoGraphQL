<?php

 
class PoP_CategoryProcessors_CDN_Hooks
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
        if (in_array(
            $thumbprint,
            array(
                POP_CDN_THUMBPRINT_POST,
                POP_CDN_THUMBPRINT_USER,
            )
        )
        ) {
                $routes = array_filter(
                array(
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS00,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS01,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS02,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS03,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS04,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS05,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS06,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS07,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS08,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS09,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS10,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS11,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS12,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS13,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS14,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS15,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS16,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS17,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS18,
                    POP_CATEGORYPOSTS_ROUTE_CATEGORYPOSTS19,
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
new PoP_CategoryProcessors_CDN_Hooks();
