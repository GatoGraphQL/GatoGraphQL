<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
class PoP_UserStanceWP_Installation
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction('PoP:system-install', array($this, 'systemInstall'));
    }

    public function systemInstall()
    {

        // Create the needed categories, only when the version is appropriate
        if (defined('POP_USERSTANCEWP_INSTALLATION_CREATECATEGORIES_VERSION') && POP_USERSTANCEWP_INSTALLATION_CREATECATEGORIES_VERSION == ApplicationInfoFacade::getInstance()->getVersion()) {
        }
    }
}

/**
 * Initialization
 */
new PoP_UserStanceWP_Installation();
