<?php
/*
Plugin Name: PoP Events Creation Processors
Description: Implementation of Events Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_EVENTSCREATIONPROCESSORS_DIR', dirname(__FILE__));
define('POP_EVENTSCREATIONPROCESSORS_PHPTEMPLATES_DIR', POP_EVENTSCREATIONPROCESSORS_DIR.'/php-templates/compiled');

class PoP_EventsCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Events Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888890);
    }
    public function init()
    {
        define('POP_EVENTSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventsCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventsCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventsCreationProcessors();
