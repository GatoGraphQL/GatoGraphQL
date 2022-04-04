<?php
use PoP\ComponentModel\State\ApplicationState;

class PoP_AutomatedEmails_WebPlatform_ResourceLoader_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter('getResourcesIncludeType', $this->getResourcesIncludeType(...));
    }

    public function getResourcesIncludeType($type)
    {

        // Making the include-type "header" will avoid types "body" or "body-inline" which are of no use in the email
        if ($this->isAutomatedEmailRoute()) {
            return 'header';
        }

        return $type;
    }

    protected function isAutomatedEmailRoute()
    {
        if (\PoP\Root\App::getState(['routing', 'is-generic'])) {
            $route = \PoP\Root\App::getState('route');
            $automatedemail_routes = PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils::getAutomatedEmailRoutes();
            return in_array($route, $automatedemail_routes);
        }

        return false;
    }
}

/**
 * Initialization
 */
new PoP_AutomatedEmails_WebPlatform_ResourceLoader_Hooks();
