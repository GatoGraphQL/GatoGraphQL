<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
\PoP\Root\App::getHookManager()->addFilter('sendemailUserwelcome:create_routes', 'popLocationpostscreationSendemailCreateRoutes');
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
