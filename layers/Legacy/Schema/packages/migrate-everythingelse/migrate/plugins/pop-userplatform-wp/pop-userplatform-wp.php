<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User Platform WordPress
Description: Implementation of User Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERPLATFORMWP_VERSION', 0.132);
define('POP_USERPLATFORMWP_DIR', dirname(__FILE__));

class PoP_UserPlatformWP
{
    public function __construct()
    {
        // Priority: after PoP User Login
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888332);
    }
    public function init()
    {
        define('POP_USERPLATFORMWP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERPLATFORMWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserPlatformWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserPlatformWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserPlatformWP();
