<?php
/*
Plugin Name: PoP User Avatar Processors
Version: 0.1
Description: Implementation of processors for the User Avatar for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERAVATARPROCESSORS_VERSION', 0.111);
define('POP_USERAVATARPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('POP_USERAVATARPROCESSORS_DIR', dirname(__FILE__));

class PoP_UserAvatarProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888850);
    }
    public function init()
    {
        define('POP_USERAVATARPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERAVATARPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserAvatarProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserAvatarProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserAvatarProcessors();
