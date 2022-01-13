<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Events Web Platform
Description: Implementation of Events Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTSWEBPLATFORM_VERSION', 0.132);
define('POP_EVENTSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_EVENTSWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_EVENTSWEBPLATFORM_PHPTEMPLATES_DIR', POP_EVENTSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_EventsWebPlatform
{
    public function __construct()
    {

        // // Priority: after PoP Locations Web Platform
        // HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888550);
        // Priority: after PoP Add Highlights Web Platform (needed by PoP Events Processors)
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888570);
    }
    public function init()
    {
        define('POP_EVENTSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventsWebPlatform();
