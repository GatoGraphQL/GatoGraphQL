<?php
/*
Plugin Name: PoP Social Login
Version: 0.1
Description: Social Login for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('POP_SOCIALLOGIN_VERSION', 0.107);
define('POP_SOCIALLOGIN_DIR', dirname(__FILE__));

class PoP_SocialLogin
{
    public function __construct()
    {

        // Priority: after PoP Notifications
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888350);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALLOGIN_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('wsl_activate');
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialLogin_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialLogin_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialLogin();
