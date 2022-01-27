<?php
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

class PoP_Migrate_Events
{
    public function __construct()
    {

        // Priority: after PoP Events
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888380);
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
        $validation = new PoP_Migrate_Events_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Migrate_Events_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Migrate_Events();
