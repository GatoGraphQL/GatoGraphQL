<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
HooksAPIFacade::getInstance()->addFilter('sendemailUserwelcome:create_routes', 'popLocationpostlinkscreationSendemailCreateRoutes');
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
