<?php
/*
Plugin Name: Events Manager for PoP Events
Version: 0.1
Description: Events Manager for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

define('EMPOPEVENTS_VERSION', 0.107);
define('EMPOPEVENTS_DIR', dirname(__FILE__));

class EM_PoPEvents
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Events_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );
        
        // Priority: after PoP Events
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 380);
    }
    public function getProviderValidationClass($class)
    {
        return EM_PoPEvents_Validation::class;
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('EMPOPEVENTS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        // require_once 'validation.php';
        $validation = new EM_PoPEvents_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new EM_PoPEvents_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new EM_PoPEvents();
