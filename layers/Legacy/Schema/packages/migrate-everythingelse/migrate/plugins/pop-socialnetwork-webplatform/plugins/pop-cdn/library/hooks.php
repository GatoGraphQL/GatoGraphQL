<?php
use PoP\Routing\URLParams;

class PoP_SocialNetworkWebPlatform_CDN_Hooks
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
                    POP_SOCIALNETWORK_ROUTE_RECOMMENDEDBY,
                    POP_SOCIALNETWORK_ROUTE_FOLLOWERS,
                    POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS,
                    POP_SOCIALNETWORK_ROUTE_UPVOTEDBY,
                    POP_SOCIALNETWORK_ROUTE_DOWNVOTEDBY,
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
new PoP_SocialNetworkWebPlatform_CDN_Hooks();
