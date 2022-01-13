<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Events Processors
Description: Implementation of Events Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTSPROCESSORS_VERSION', 0.132);
define('POP_EVENTSPROCESSORS_DIR', dirname(__FILE__));
define('POP_EVENTSPROCESSORS_PHPTEMPLATES_DIR', POP_EVENTSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_EventsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Locations Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888880);
    }
    public function init()
    {
        define('POP_EVENTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventsProcessors();
