<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Theme
Description: Implementation of Theme for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_THEME_VERSION', 0.132);
define('POP_THEME_DIR', dirname(__FILE__));

class PoP_Theme
{
    public function __construct()
    {

        // Priority: after PoP Engine
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888110);
    }
    public function init()
    {
        define('POP_THEME_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_THEME_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Theme_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Theme_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Theme();
