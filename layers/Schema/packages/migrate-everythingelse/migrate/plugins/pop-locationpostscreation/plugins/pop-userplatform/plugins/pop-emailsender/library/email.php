<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
HooksAPIFacade::getInstance()->addFilter('sendemailUserwelcome:create_routes', 'popLocationpostscreationSendemailCreateRoutes');
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
