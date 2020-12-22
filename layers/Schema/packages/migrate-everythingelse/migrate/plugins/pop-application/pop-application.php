<?php
/*
Plugin Name: PoP Application
Version: 0.1
Description: The foundation for a PoP Application
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_APPLICATION_VERSION', 0.106);
define('POP_APPLICATION_DIR', dirname(__FILE__));

class PoP_Application
{
    public function __construct()
    {
        
        // Priority: new section, after PoP CMS Model section
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 300);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_APPLICATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Application_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Application_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Application();
