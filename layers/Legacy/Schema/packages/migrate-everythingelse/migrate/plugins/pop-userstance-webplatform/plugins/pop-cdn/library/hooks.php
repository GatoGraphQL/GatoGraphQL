<?php

 
class PoP_UserStance_CDN_Hooks
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
                    POP_USERSTANCE_ROUTE_STANCES,
                    POP_USERSTANCE_ROUTE_STANCES_PRO,
                    POP_USERSTANCE_ROUTE_STANCES_AGAINST,
                    POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
                    POP_USERSTANCE_ROUTE_STANCES_PRO_GENERAL,
                    POP_USERSTANCE_ROUTE_STANCES_AGAINST_GENERAL,
                    POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_GENERAL,
                    POP_USERSTANCE_ROUTE_STANCES_PRO_ARTICLE,
                    POP_USERSTANCE_ROUTE_STANCES_AGAINST_ARTICLE,
                    POP_USERSTANCE_ROUTE_STANCES_NEUTRAL_ARTICLE,
                    POP_USERSTANCE_ROUTE_STANCES_BYORGANIZATIONS,
                    POP_USERSTANCE_ROUTE_STANCES_BYINDIVIDUALS,
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
new PoP_UserStance_CDN_Hooks();
