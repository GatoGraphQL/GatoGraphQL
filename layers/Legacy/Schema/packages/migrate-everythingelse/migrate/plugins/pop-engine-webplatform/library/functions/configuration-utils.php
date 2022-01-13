<?php
use PoP\Engine\Facades\CMS\CMSServiceFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_WebPlatform_ConfigurationUtils
{
    public static function getAllowedDomains()
    {
        $cmsService = CMSServiceFacade::getInstance();
        $homeurl = $cmsService->getSiteURL();
        return array_values(array_unique(HooksAPIFacade::getInstance()->applyFilters(
            'pop_modulemanager:allowed_domains',
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
