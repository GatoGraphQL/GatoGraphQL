<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_AutomatedEmails_WebPlatform_ResourceLoader_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter('getResourcesIncludeType', array($this, 'getResourcesIncludeType'));
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
        $vars = ApplicationState::getVars();
        if ($vars['routing-state']['is-standard']) {
            $route = $vars['route'];
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
