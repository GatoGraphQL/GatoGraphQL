<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Event Links  Processors
Description: Implementation of Event Links  Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTLINKSPROCESSORS_VERSION', 0.132);
define('POP_EVENTLINKSPROCESSORS_DIR', dirname(__FILE__));
define('POP_EVENTLINKSPROCESSORS_PHPTEMPLATES_DIR', POP_EVENTLINKSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_EventLinksProcessors
{
    public function __construct()
    {

        // Priority: after PoP Events Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888900);
    }
    public function init()
    {
        define('POP_EVENTLINKSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTLINKSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventLinksProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventLinksProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventLinksProcessors();
