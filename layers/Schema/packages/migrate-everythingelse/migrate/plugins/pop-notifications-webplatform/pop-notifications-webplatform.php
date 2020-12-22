<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/*
Plugin Name: PoP Notifications Web Platform
Description: Implementation of Notifications Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NOTIFICATIONSWEBPLATFORM_VERSION', 0.132);
define('POP_NOTIFICATIONSWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_NOTIFICATIONSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_NOTIFICATIONSWEBPLATFORM_PHPTEMPLATES_DIR', POP_NOTIFICATIONSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_NotificationsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP User Platform Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 540);
    }
    public function init()
    {
        define('POP_NOTIFICATIONSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_NOTIFICATIONSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NotificationsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NotificationsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NotificationsWebPlatform();
