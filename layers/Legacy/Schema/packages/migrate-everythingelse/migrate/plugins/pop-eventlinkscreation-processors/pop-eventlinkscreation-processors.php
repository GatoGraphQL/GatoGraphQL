<?php
/*
Plugin Name: PoP Event Links Creation Processors
Description: Implementation of Event Links Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTLINKSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_EVENTLINKSCREATIONPROCESSORS_DIR', dirname(__FILE__));
define('POP_EVENTLINKSCREATIONPROCESSORS_PHPTEMPLATES_DIR', POP_EVENTLINKSCREATIONPROCESSORS_DIR.'/php-templates/compiled');

class PoP_EventLinksCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Events Creation Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888900);
    }
    public function init()
    {
        define('POP_EVENTLINKSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTLINKSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventLinksCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventLinksCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventLinksCreationProcessors();
