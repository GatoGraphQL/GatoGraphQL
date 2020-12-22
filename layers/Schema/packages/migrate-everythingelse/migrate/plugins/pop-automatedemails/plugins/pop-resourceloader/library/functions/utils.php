<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils
{
    public static function getAutomatedEmailRoutes()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils:automatedemail-routes',
            array()
        );
    }
}
