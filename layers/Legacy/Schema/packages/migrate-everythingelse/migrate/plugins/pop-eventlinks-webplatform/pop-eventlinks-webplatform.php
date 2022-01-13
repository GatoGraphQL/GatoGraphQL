<?php
/*
Plugin Name: PoP Event Links Web Platform
Description: Implementation of EventLinks Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTLINKSWEBPLATFORM_VERSION', 0.132);
define('POP_EVENTLINKSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_EVENTLINKSWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_EVENTLINKSWEBPLATFORM_PHPTEMPLATES_DIR', POP_EVENTLINKSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_EventLinksWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Events Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888580);
    }
    public function init()
    {
        define('POP_EVENTLINKSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTLINKSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventLinksWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventLinksWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventLinksWebPlatform();
