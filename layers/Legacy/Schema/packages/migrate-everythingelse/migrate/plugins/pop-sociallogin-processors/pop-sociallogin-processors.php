<?php
/*
Plugin Name: PoP Social Login Processors
Version: 0.1
Description: Collection of Processors for Social Login for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('POP_SOCIALLOGINPROCESSORS_VERSION', 0.109);
define('POP_SOCIALLOGINPROCESSORS_DIR', dirname(__FILE__));

class PoP_SocialLoginProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888850);
    }

    public function init()
    {
        define('POP_SOCIALLOGINPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALLOGINPROCESSORS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('wsl_activate');
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialLoginProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialLoginProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialLoginProcessors();
