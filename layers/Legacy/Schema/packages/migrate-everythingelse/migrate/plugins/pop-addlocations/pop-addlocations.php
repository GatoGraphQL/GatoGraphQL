<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Locations
Description: Implementation of Add Locations for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDLOCATIONS_VERSION', 0.132);
define('POP_ADDLOCATIONS_DIR', dirname(__FILE__));

class PoP_AddLocations
{
    public function __construct()
    {

        // Priority: after PoP Locations, but before EM PoP Locations
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888349);
    }
    public function init()
    {
        define('POP_ADDLOCATIONS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDLOCATIONS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddLocations_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddLocations_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddLocations();
