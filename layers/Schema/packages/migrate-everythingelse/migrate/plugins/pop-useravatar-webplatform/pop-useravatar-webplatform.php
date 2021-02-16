<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/*
Plugin Name: PoP User Avatar Web Platform
Description: Implementation of User Avatar Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERAVATARWEBPLATFORM_VERSION', 0.132);
define('POP_USERAVATARWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_USERAVATARWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_USERAVATARWEBPLATFORM_PHPTEMPLATES_DIR', POP_USERAVATARWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_UserAvatarWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Notifications Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }
    public function init()
    {
        define('POP_USERAVATARWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERAVATARWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserAvatarWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserAvatarWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserAvatarWebPlatform();
