<?php
/*
Plugin Name: PoP Bootstrap Collection Web Platform
Description: Implementation of Bootstrap Collection Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VERSION', 0.132);
define('POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_BootstrapCollectionWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Bootstrap Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888450);
    }
    public function init()
    {
        define('POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_BOOTSTRAPCOLLECTIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BootstrapCollectionWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BootstrapCollectionWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BootstrapCollectionWebPlatform();
