<?php

 
class PoP_NoSearchCategoryProcessors_CDN_Hooks
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
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS00,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS01,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS02,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS03,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS04,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS05,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS06,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS07,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS08,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS09,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS10,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS11,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS12,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS13,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS14,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS15,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS16,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS17,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS18,
                    POP_NOSEARCHCATEGORYPOSTS_ROUTE_NOSEARCHCATEGORYPOSTS19,
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
new PoP_NoSearchCategoryProcessors_CDN_Hooks();
