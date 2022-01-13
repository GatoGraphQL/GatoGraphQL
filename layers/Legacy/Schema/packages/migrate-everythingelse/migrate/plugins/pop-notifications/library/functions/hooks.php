<?php

 
class Notifications_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
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
