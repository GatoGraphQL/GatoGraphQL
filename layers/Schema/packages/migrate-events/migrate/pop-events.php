<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/*
Plugin Name: PoP Events
Description: Implementation of Events for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTS_VERSION', 0.132);
define('POP_EVENTS_DIR', dirname(__FILE__));

class PoP_Events
{
    public function __construct()
    {

        // // Priority: after PoP Locations
        // HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 350);
        // Priority: after PoP Add Highlights (needed by PoP Events Processors)
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 370);
    }
    public function init()
    {
        define('POP_EVENTS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Events_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Events_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Events();
