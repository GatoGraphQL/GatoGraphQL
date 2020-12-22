<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ResourceLoader_WebPlatformHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter('PoP_HTMLCSSPlatform_ConfigurationUtils:registerScriptsAndStylesDuringInit', array($this, 'registerScriptsAndStylesDuringInit'));
    }

    public function registerScriptsAndStylesDuringInit($register)
    {
        if (PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {
            return false;
        }

        return $register;
    }
}

/**
 * Initialization
 */
new PoP_ResourceLoader_WebPlatformHooks();
