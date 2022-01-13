<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

/**
 * Create page on the initial user welcome email
 */
HooksAPIFacade::getInstance()->addFilter('sendemailUserwelcome:create_routes', 'popEventlinkscreationWassupCreateRoutes');
function popEventlinkscreationWassupCreateRoutes($routes)
{
    $routes = array_merge(
        $routes,
        array(
            POP_EVENTLINKSCREATION_ROUTE_ADDEVENTLINK,
        )
    );

    return $routes;
}
