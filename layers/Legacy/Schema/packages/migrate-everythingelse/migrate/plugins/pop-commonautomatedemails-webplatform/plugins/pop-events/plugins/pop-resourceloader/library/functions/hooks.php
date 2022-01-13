<?php

class PoP_CommonAutomatedEmails_EM_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils:automatedemail-routes',
            array($this, 'getAutomatedEmailRoutes')
        );
    }

    public function getAutomatedEmailRoutes($routes)
    {
        $routes[] = POP_COMMONAUTOMATEDEMAILS_ROUTE_UPCOMINGEVENTS_WEEKLY;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_CommonAutomatedEmails_EM_ResourceLoader_Hooks();
