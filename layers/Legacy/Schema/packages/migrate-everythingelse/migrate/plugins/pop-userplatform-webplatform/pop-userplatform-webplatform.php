<?php
/*
Plugin Name: PoP User Platform Web Platform
Description: Implementation of User Platform Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERPLATFORMWEBPLATFORM_VERSION', 0.132);
define('POP_USERPLATFORMWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_USERPLATFORMWEBPLATFORM_PHPTEMPLATES_DIR', POP_USERPLATFORMWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_UserPlatformWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP User Login Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888530);
    }
    public function init()
    {
        define('POP_USERPLATFORMWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERPLATFORMWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserPlatformWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserPlatformWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserPlatformWebPlatform();
