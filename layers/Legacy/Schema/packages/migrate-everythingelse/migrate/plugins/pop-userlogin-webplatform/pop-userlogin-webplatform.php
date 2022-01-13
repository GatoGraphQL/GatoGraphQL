<?php
/*
Plugin Name: PoP User Login Web Platform
Description: Implementation of User Login Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERLOGINWEBPLATFORM_VERSION', 0.132);
define('POP_USERLOGINWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_USERLOGINWEBPLATFORM_PHPTEMPLATES_DIR', POP_USERLOGINWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_UserLoginWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Email Sender Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888520);
    }
    public function init()
    {
        define('POP_USERLOGINWEBPLATFORM_URL', plugins_url('', __FILE__));

        define('POP_USERLOGIN_ASSETDESTINATION_DIR', POP_CONTENT_DIR.'/userlogin');
        define('POP_USERLOGIN_ASSETDESTINATION_URL', POP_CONTENT_URL.'/userlogin');

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERLOGINWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserLoginWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserLoginWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserLoginWebPlatform();
