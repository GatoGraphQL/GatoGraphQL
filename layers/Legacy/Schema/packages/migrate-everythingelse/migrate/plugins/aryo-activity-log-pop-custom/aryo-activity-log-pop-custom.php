<?php
/*
Plugin Name: Activity Log for PoP Custom Version
Version: 0.1
Description: Custom version of Activity Log for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('AAL_POPCUSTOM_VERSION', 0.107);
define('AAL_POPCUSTOM_DIR', dirname(__FILE__));

class AAL_PoPCustom
{
    public function __construct()
    {

        // Priority: after AAL PoP
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888370);
    }

    public function init()
    {
        if ($this->validate()) {
            // Initialize plugin
            $this->initialize();

            // Set as initialized
            define('AAL_POPCUSTOM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('AAL_Main');
        return true;
        include_once 'validation.php';
        $validation = new AAL_PoPCustom_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new AAL_PoPCustom_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new AAL_PoPCustom();
