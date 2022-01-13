<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

 
class Notifications_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'loadingLatestRoutes',
            array($this, 'getLoadingLatestRoutes')
        );
    }

    public function getLoadingLatestRoutes($routes)
    {
        $routes[] = POP_NOTIFICATIONS_ROUTE_NOTIFICATIONS;
        return $routes;
    }
}

/**
 * Initialize
 */
new Notifications_Hooks();
