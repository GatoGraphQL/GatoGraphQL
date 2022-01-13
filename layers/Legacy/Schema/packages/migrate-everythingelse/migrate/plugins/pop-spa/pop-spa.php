<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Single-Page Application
Description: Implementation of SPA capabilities for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SPA_VERSION', 0.157);
define('POP_SPA_DIR', dirname(__FILE__));

class PoP_SPA
{
    public function __construct()
    {

        // Priority: after PoP Engine
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888110);
    }
    public function init()
    {
        define('POP_SPA_URL', plugins_url('', __FILE__));
        if ($this->validate()) {
            $this->initialize();
            define('POP_SPA_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SPA_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SPA_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SPA();
