<?php
use PoP\Hooks\Facades\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
HooksAPIFacade::getInstance()->addFilter('sendemailUserwelcome:create_routes', 'popContentpostlinkscreationSendemailCreateRoutes');
function popContentpostlinkscreationSendemailCreateRoutes($routes)
{
    $routes = array_merge(
        $routes,
        array(
            POP_CONTENTPOSTLINKSCREATION_ROUTE_ADDCONTENTPOSTLINK,
        )
    );

    return $routes;
}
