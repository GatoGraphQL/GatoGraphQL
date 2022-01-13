<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Master Collection Web Platform
Description: Implementation of Master Collection Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MASTERCOLLECTIONWEBPLATFORM_VERSION', 0.132);
define('POP_MASTERCOLLECTIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_MASTERCOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION', 0.200);
define('POP_MASTERCOLLECTIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_MASTERCOLLECTIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_MasterCollectionWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Server-Side Rendering
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888420);
    }
    public function init()
    {
        define('POP_MASTERCOLLECTIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_MASTERCOLLECTIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        require_once 'validation.php';
        $validation = new PoP_MasterCollectionWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        require_once 'initialization.php';
        $initialization = new PoP_MasterCollectionWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MasterCollectionWebPlatform();
