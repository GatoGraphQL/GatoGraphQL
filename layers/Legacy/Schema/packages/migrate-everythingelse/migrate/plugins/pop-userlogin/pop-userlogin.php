<?php
/*
Plugin Name: PoP User Login
Description: Implementation of User Login for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERLOGIN_VERSION', 0.132);
define('POP_USERLOGIN_DIR', dirname(__FILE__));

class PoP_UserLogin
{
    public function __construct()
    {

        // Priority: after PoP Email Sender
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888320);
    }
    public function init()
    {
        define('POP_USERLOGIN_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERLOGIN_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserLogin_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserLogin_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserLogin();
