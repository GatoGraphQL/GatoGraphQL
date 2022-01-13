<?php

/**
 * Create page on the initial user welcome email
 */
\PoP\Root\App::getHookManager()->addFilter('sendemailUserwelcome:create_routes', 'emWassupCreateRoutes');
function emWassupCreateRoutes($routes)
{
    $routes = array_merge(
        $routes,
        array(
            POP_EVENTSCREATION_ROUTE_ADDEVENT,
        )
    );

    return $routes;
}
