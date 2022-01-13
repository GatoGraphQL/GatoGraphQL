<?php
/*
Plugin Name: PoP System
Version: 0.1
Description: Access system functionalities for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SYSTEM_VERSION', 0.210);
define('POP_SYSTEM_DIR', dirname(__FILE__));

class PoP_System
{
    public function __construct()
    {

        // Priority: after PoP Forms
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888120);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_SYSTEM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_System_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_System_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_System();
