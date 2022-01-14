<?php
use PoP\Root\Routing\URLParams;

class PoP_Wassup_CDN_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_CDN_FileReproduction_ThumbprintsConfig:criteriaitems:thumbprint:noParamValues',
            array($this, 'getThumbprintNoparamvalues'),
            10,
            2
        );
    }

    // Comment Leo 31/01/2017: No need to define the thumbprints below, since the author URL (eg: getpop.org/en/u/leo/)
    // has already been set to use thumbprint POST
    // function getThumbprintParamvalues($paramvalues, $thumbprint) {

        //     //     if ($thumbprint == POP_CDN_THUMBPRINT_USER) {

    //         $routes = array(
    //             POP_ROUTE_DESCRIPTION,
    //             RoutingRoutes::$MAIN,
    //             POPTHEME_WASSUP_ROUTE_SUMMARY,
    //             POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
    //             POP_ROUTE_AUTHORS,
    //         );
    //         foreach ($routes as $route) {

    //             // Array of: elem[0] = URL param, elem[1] = value
    //             $paramvalues[] = array(
    //                 URLParams::ROUTE,
    //                 $route.'/'
    //             );
    //         }
    //     }
    //     elseif ($thumbprint == POP_CDN_THUMBPRINT_POST) {

    //         $routes = array(
    //             RoutingRoutes::$MAIN,
    //             POPTHEME_WASSUP_ROUTE_SUMMARY,
    //             POP_RELATEDPOSTS_ROUTE_RELATEDCONTENT,
    //         );
    //         foreach ($routes as $route) {

    //             $paramvalues[] = array(
    //                 URLParams::ROUTE,
    //                 $route.'/'
    //             );
    //         }
    //     }

    //     return $paramvalues;
    // }

    public function getThumbprintNoparamvalues($noparamvalues, $thumbprint)
    {

        // Please notice:
        // getpop.org/en/u/leo/ has thumbprints POST + USER, but
        // getpop.org/en/u/leo/?tab=description needs only thumbprint USER
        // for that, we have added criteria "noParamValues", checking that it is thumbprint POST
        // as long as the specified tabs (description, followers, etc) are not in the URL
        // This must be added on those hooks.php files implementing the corresponding pages
        // (eg: pop-coreprocessors handles tab=description, etc)
        if ($thumbprint == POP_CDN_THUMBPRINT_POST) {
            $routes = array_filter(
                array(
                    POP_ROUTE_DESCRIPTION,
                    POP_ROUTE_AUTHORS,
                )
            );

            // Add the values to the configuration
            foreach ($routes as $route) {
                // Array of: elem[0] = URL param, elem[1] = value
                $noparamvalues[] = array(
                    URLParams::ROUTE,
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
new PoP_Wassup_CDN_Hooks();
