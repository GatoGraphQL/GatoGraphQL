<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
HooksAPIFacade::getInstance()->addFilter('sendemailUserwelcome:create_routes', 'popPostscreationSendEmailCreateRoutes');
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
