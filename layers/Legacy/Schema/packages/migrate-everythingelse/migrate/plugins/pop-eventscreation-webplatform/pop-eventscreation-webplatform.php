<?php
/*
Plugin Name: PoP Events Creation Web Platform
Description: Implementation of Events Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTSCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_EVENTSCREATIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_EVENTSCREATIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_EVENTSCREATIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_EventsCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Events Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888580);
    }
    public function init()
    {
        define('POP_EVENTSCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTSCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventsCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventsCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventsCreationWebPlatform();
