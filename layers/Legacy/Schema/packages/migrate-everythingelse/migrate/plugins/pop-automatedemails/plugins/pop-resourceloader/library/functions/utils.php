<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils
{
    public static function getAutomatedEmailRoutes()
    {
        return \PoP\Root\App::getHookManager()->applyFilters(
            'PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils:automatedemail-routes',
            array()
        );
    }
}
