<?php

/**
 * Create page on the initial user welcome email
 */
\PoP\Root\App::addFilter('sendemailUserwelcome:create_routes', 'popLocationpostscreationSendemailCreateRoutes');
function popLocationpostscreationSendemailCreateRoutes($routes)
{
    $routes = array_merge(
        $routes,
        array(
            POP_LOCATIONPOSTSCREATION_ROUTE_ADDLOCATIONPOST,
        )
    );

    return $routes;
}
