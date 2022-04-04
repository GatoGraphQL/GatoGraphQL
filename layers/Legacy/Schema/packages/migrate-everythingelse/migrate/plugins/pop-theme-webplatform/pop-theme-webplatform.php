<?php
/*
Plugin Name: PoP Theme Web Platform
Description: Implementation of Theme for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_THEMEWEBPLATFORM_VERSION', 0.157);
define('POP_THEMEWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_ThemeWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Engine Web Platform, inner circle
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888402);
    }
    public function init()
    {
        define('POP_THEMEWEBPLATFORM_URL', plugins_url('', __FILE__));
        if ($this->validate()) {
            $this->initialize();
            define('POP_THEMEWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ThemeWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ThemeWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ThemeWebPlatform();
