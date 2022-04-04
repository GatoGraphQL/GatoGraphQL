<?php

class PoP_CommonAutomatedEmails_AAL_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils:automatedemail-routes',
            $this->getAutomatedEmailRoutes(...)
        );
    }

    public function getAutomatedEmailRoutes($routes)
    {
        $routes[] = POP_COMMONAUTOMATEDEMAILS_ROUTE_LATESTNOTIFICATIONS_DAILY;
        return $routes;
    }
}

/**
 * Initialization
 */
new PoP_CommonAutomatedEmails_AAL_ResourceLoader_Hooks();
