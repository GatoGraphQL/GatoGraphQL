<?php
/*
Plugin Name: PoP Theme Helpers
Version: 0.1
Description: The foundation for a PoP Theme Helpers
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_THEMEHELPERS_VERSION', 0.106);
define('POP_THEMEHELPERS_DIR', dirname(__FILE__));

class PoP_ThemeHelpers
{
    public function __construct()
    {

        // Priority: new section, after PoP Application Processors section
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888900);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_THEMEHELPERS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ThemeHelpers_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ThemeHelpers_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ThemeHelpers();
