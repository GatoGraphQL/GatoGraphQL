<?php
/*
Plugin Name: PoP User Platform
Description: Implementation of User Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERPLATFORM_VERSION', 0.132);
define('POP_USERPLATFORM_DIR', dirname(__FILE__));

class PoP_UserPlatform
{
    public function __construct()
    {

        // Priority: after PoP User Login
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888330);
    }
    public function init()
    {
        define('POP_USERPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserPlatform();
