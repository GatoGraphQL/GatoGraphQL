<?php
/*
Plugin Name: PoP Theme Wassup GetPoP Demo
Version: 0.1
Description: Integration of GetPoP Demo for PoP Theme Wassup
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POPTHEME_WASSUP_GETPOPDEMO_VERSION', 0.109);
define('POPTHEME_WASSUP_GETPOPDEMO_DIR', dirname(__FILE__));

class PoPTheme_Wassup_GetPoPDemo
{
    public function __construct()
    {

        // Priority: mid-section, after PoP Theme Wassup section, and after PoPTheme Wassup Category Processors
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 8881050);
    }

    public function init()
    {
        define('POPTHEME_WASSUP_GETPOPDEMO_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POPTHEME_WASSUP_GETPOPDEMO_INITIALIZED', true);
        } else {
            define('POP_STARTUP_INITIALIZED', false);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoPTheme_Wassup_GetPoPDemo_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoPTheme_Wassup_GetPoPDemo_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup_GetPoPDemo();
