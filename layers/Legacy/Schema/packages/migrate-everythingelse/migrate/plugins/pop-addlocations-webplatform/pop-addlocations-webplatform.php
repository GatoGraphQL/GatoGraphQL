<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Locations Web Platform
Description: Implementation of Add Locations Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDLOCATIONSWEBPLATFORM_VERSION', 0.132);
define('POP_ADDLOCATIONSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_ADDLOCATIONSWEBPLATFORM_PHPTEMPLATES_DIR', POP_ADDLOCATIONSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_AddLocationsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Locations Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }
    public function init()
    {
        define('POP_ADDLOCATIONSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDLOCATIONSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddLocationsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddLocationsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddLocationsWebPlatform();
