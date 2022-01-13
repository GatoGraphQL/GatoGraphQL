<?php

/**
 * Create page on the initial user welcome email
 */
\PoP\Root\App::addFilter('sendemailUserwelcome:create_routes', 'popLocationpostlinkscreationSendemailCreateRoutes');
function popLocationpostlinkscreationSendemailCreateRoutes($routes)
{
    $routes = array_merge(
        $routes,
        array(
            POP_LOCATIONPOSTLINKSCREATION_ROUTE_ADDLOCATIONPOSTLINK,
        )
    );

    return $routes;
}
