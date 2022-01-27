<?php

/**
 * Create page on the initial user welcome email
 */
\PoP\Root\App::addFilter('sendemailUserwelcome:create_routes', 'popPostscreationSendEmailCreateRoutes');
function popPostscreationSendEmailCreateRoutes($routes)
{
    $routes = array_merge(
        $routes,
        array_filter(
            array(
                POP_POSTSCREATION_ROUTE_ADDPOST,
            )
        )
    );

    return $routes;
}
