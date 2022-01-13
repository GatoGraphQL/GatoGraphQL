<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ResourceLoader_WebPlatformHooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter('PoP_HTMLCSSPlatform_ConfigurationUtils:registerScriptsAndStylesDuringInit', array($this, 'registerScriptsAndStylesDuringInit'));
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
