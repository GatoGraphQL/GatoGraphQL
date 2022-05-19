<?php
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoP_WebPlatform_ConfigurationUtils
{
    public static function getAllowedDomains()
    {
        $cmsService = CMSServiceFacade::getInstance();
        $homeurl = $cmsService->getSiteURL();
        return array_values(array_unique(\PoP\Root\App::applyFilters(
            'pop_componentmanager:allowed_domains',
            array(
                $homeurl,
            )
        )));
    }

    public static function registerScriptsAndStylesDuringInit()
    {
        return PoP_HTMLCSSPlatform_ConfigurationUtils::registerScriptsAndStylesDuringInit();
    }
}
