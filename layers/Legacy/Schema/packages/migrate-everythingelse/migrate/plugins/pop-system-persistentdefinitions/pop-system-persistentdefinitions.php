<?php
/*
Plugin Name: PoP Persistent Definitions System
Version: 0.1
Description: Implementation of Module Definitions for PoP modules
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_PERSISTENTDEFINITIONSSYSTEM_VERSION', 0.108);
define('POP_PERSISTENTDEFINITIONSSYSTEM_DIR', dirname(__FILE__));

class PoP_PersistentDefinitionsSystem
{
    public function __construct()
    {

        // Priority: after PoP System
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888220);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_PERSISTENTDEFINITIONSSYSTEM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_PersistentDefinitionsSystem_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PersistentDefinitionsSystem_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PersistentDefinitionsSystem();
