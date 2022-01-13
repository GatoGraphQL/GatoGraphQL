<?php
/*
Plugin Name: PoP Google Analytics
Version: 0.1
Description: Integration with Google Analytics for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('POP_GOOGLEANALYTICS_VERSION', 0.110);
define('POP_GOOGLEANALYTICS_DIR', dirname(__FILE__));

class PoP_GoogleAnalytics
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('POP_GOOGLEANALYTICS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_GOOGLEANALYTICS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_GoogleAnalytics_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_GoogleAnalytics_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_GoogleAnalytics();
