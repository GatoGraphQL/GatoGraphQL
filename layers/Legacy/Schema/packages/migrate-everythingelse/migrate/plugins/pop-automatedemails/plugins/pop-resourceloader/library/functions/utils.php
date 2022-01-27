<?php

class PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils
{
    public static function getAutomatedEmailRoutes()
    {
        return \PoP\Root\App::applyFilters(
            'PoP_AutomatedEmails_WebPlatform_ResourceLoader_Utils:automatedemail-routes',
            array()
        );
    }
}
