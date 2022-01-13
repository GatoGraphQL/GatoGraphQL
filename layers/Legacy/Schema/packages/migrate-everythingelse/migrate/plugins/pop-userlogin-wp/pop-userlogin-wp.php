<?php
/*
Plugin Name: PoP User Login WordPress
Description: Implementation of User Login for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERLOGINWP_VERSION', 0.132);
define('POP_USERLOGINWP_DIR', dirname(__FILE__));

class PoP_UserLoginWP
{
    public function __construct()
    {
        // Priority: after PoP Email Sender
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888322);
    }
    public function init()
    {
        define('POP_USERLOGINWP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERLOGINWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserLoginWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserLoginWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserLoginWP();
