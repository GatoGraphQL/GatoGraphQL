<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/*
Plugin Name: PoP Locations
Description: Implementation of Locations for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONS_VERSION', 0.132);
define('POP_LOCATIONS_DIR', dirname(__FILE__));

class PoP_Locations
{
    public function __construct()
    {

        // Priority: after PoP User Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 340);
    }
    public function init()
    {
        define('POP_LOCATIONS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Locations_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Locations_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Locations();
