<?php
/*
Plugin Name: WP Super Cache for PoP
Version: 0.1
Description: Integration with plug-in WP Super Cache for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('WPSC_POP_VERSION', 0.110);
define('WPSC_POP_DIR', dirname(__FILE__));

class WPSC_PoP
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888310);
    }

    public function init()
    {
        define('WPSC_POP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('WPSC_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('wpsupercache_site_admin');
        return true;
        include_once 'validation.php';
        $validation = new WPSC_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new WPSC_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new WPSC_PoP();
