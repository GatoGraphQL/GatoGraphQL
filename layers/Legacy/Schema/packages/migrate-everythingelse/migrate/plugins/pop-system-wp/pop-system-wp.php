<?php
/*
Plugin Name: PoP System WordPress
Version: 0.1
Description: Access system functionalities for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SYSTEMWP_VERSION', 0.210);
define('POP_SYSTEMWP_DIR', dirname(__FILE__));

class PoP_SystemWP
{
    public function __construct()
    {
        // Priority: after PoP Forms
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888122);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_SYSTEMWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SystemWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SystemWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SystemWP();
