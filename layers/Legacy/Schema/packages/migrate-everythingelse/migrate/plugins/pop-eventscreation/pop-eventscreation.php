<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Events Creation
Description: Implementation of Events Creation for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTSCREATION_VERSION', 0.132);
define('POP_EVENTSCREATION_DIR', dirname(__FILE__));

class PoP_EventsCreation
{
    public function __construct()
    {

        // Priority: after PoP Events
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888380);
    }
    public function init()
    {
        define('POP_EVENTSCREATION_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTSCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventsCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventsCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventsCreation();
