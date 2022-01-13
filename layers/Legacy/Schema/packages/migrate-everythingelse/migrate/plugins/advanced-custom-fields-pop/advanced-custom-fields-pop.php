<?php
/*
Plugin Name: Advanced Custom Fields for PoP
Version: 0.1
Description: Integration with plug-in Advanced Custom Fields for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('ACF_POP_VERSION', 0.110);
define('ACF_POP_DIR', dirname(__FILE__));

class ACF_PoP
{
    public function __construct()
    {

        // Priority: after PoP Application
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }

    public function init()
    {
        define('ACF_POP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('ACF_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('acf');
        return true;
        include_once 'validation.php';
        $validation = new ACF_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new ACF_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new ACF_PoP();
