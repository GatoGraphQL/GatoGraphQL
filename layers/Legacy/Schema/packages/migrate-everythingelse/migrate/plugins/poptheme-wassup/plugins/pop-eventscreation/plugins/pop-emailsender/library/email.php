<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
HooksAPIFacade::getInstance()->addFilter('sendemailUserwelcome:create_routes', 'emWassupCreateRoutes');
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
