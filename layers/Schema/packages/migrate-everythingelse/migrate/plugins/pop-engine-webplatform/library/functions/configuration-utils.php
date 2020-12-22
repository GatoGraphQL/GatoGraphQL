<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_WebPlatform_ConfigurationUtils
{
    public static function getAllowedDomains()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $homeurl = $cmsengineapi->getSiteURL();
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
