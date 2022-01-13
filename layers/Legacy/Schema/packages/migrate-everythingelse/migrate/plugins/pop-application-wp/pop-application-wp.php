<?php
/*
Plugin Name: PoP WordPress Application
Version: 0.1
Description: Implementation of WordPress functions for PoP Application
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_APPLICATIONWP_VERSION', 0.106);
define('POP_APPLICATIONWP_DIR', dirname(__FILE__));

class PoP_ApplicationWP
{
    public function __construct()
    {

        // Priority: mid section, after PoP Application section
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_APPLICATIONWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ApplicationWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ApplicationWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ApplicationWP();
