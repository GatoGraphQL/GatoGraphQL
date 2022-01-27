<?php
/*
Plugin Name: Wordpress Social Login for PoP Web Platform
Version: 0.1
Description: Wordpress Social Login Web Platform implementation for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('WSL_POPWEBPLATFORM_VERSION', 0.107);
define('WSL_POPWEBPLATFORM_DIR', dirname(__FILE__));

class WSL_PoPWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Social Login Web Platform
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888560);
    }

    public function init()
    {
        define('WSL_POPWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('WSL_POPWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('wsl_activate');
        return true;
        include_once 'validation.php';
        $validation = new WSL_PoPWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new WSL_PoPWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new WSL_PoPWebPlatform();
