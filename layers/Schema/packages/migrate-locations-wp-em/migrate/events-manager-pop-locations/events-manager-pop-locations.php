<?php
/*
Plugin Name: Events Manager for PoP Locations
Version: 0.1
Description: Events Manager for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

define('EMPOPLOCATIONS_VERSION', 0.107);
define('EMPOPLOCATIONS_DIR', dirname(__FILE__));

class EM_PoPLocations
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Locations_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );
        
        // Priority: after PoP Locations
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 350);
    }
    public function getProviderValidationClass($class)
    {
        return EM_PoPLocations_Validation::class;
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('EMPOPLOCATIONS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        // require_once 'validation.php';
        $validation = new EM_PoPLocations_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new EM_PoPLocations_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new EM_PoPLocations();
