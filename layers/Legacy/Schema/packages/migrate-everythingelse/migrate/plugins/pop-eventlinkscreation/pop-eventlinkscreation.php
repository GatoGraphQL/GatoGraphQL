<?php
/*
Plugin Name: PoP Event Links Creation
Description: Implementation of Event Links Creation for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTLINKSCREATION_VERSION', 0.132);
define('POP_EVENTLINKSCREATION_DIR', dirname(__FILE__));

class PoP_EventLinksCreation
{
    public function __construct()
    {

        // Priority: after PoP Events Creation
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888390);
    }
    public function init()
    {
        define('POP_EVENTLINKSCREATION_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTLINKSCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventLinksCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventLinksCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventLinksCreation();
