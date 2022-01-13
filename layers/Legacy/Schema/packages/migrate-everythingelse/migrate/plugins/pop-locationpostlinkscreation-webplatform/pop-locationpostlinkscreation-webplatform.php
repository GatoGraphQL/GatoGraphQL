<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Location Post Links Creation Web Platform
Description: Implementation of LocationPostLinks Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTLINKSCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_LOCATIONPOSTLINKSCREATIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_LOCATIONPOSTLINKSCREATIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_LOCATIONPOSTLINKSCREATIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_LocationPostLinksCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Location Posts Creation Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888570);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTLINKSCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTLINKSCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostLinksCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostLinksCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinksCreationWebPlatform();
