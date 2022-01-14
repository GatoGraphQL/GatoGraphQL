<?php

use PoP\Root\Constants\Params;

class PoP_UserCommunities_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:startsWith:partial',
            array($this, 'getThumbprintPartialpaths'),
            10,
            2
        );
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:noParamValues',
            array($this, 'getThumbprintNoparamvalues'),
            10,
            2
        );
    }

    public function getThumbprintPartialpaths($paths, $thumbprint)
    {
        if ($thumbprint == POP_CDN_THUMBPRINT_USER) {
            $routes = array_filter(
                array(
                    POP_USERCOMMUNITIES_ROUTE_COMMUNITIES,
                )
            );
            foreach ($routes as $route) {
                $paths[] = $route.'/';
            }
        }

        return $paths;
    }

    public function getThumbprintNoparamvalues($noparamvalues, $thumbprint)
    {

        // Please notice:
        // getpop.org/en/u/pop/ has thumbprints POST + USER, but
        // getpop.org/en/u/pop/?tab=members needs only thumbprint USER
        if ($thumbprint == POP_CDN_THUMBPRINT_POST) {
            $routes = array_filter(
                array(
                    POP_USERCOMMUNITIES_ROUTE_MEMBERS,
                    POP_USERCOMMUNITIES_ROUTE_COMMUNITYPLUSMEMBERS,
                )
            );

            // Add the values to the configuration
            foreach ($routes as $route) {
                // Array of: elem[0] = URL param, elem[1] = value
                $noparamvalues[] = array(
                    Params::ROUTE,
                    $route
                );
            }
        }

        return $noparamvalues;
    }
}

/**
 * Initialize
 */
new PoP_UserCommunities_CDN_Hooks();
