<?php
/*
Plugin Name: PoP Bootstrap Web Platform
Version: 0.1
Description: Plug-in providing the webplatform for Bootstrap for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BOOTSTRAPWEBPLATFORM_VERSION', 0.216);
define('POP_BOOTSTRAPWEBPLATFORM_VENDORRESOURCESVERSION', 0.200);
define('POP_BOOTSTRAPWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_BOOTSTRAPWEBPLATFORM_PHPTEMPLATES_DIR', POP_BOOTSTRAPWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_BootstrapWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Service Workers
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888440);
    }
    public function init()
    {
        define('POP_BOOTSTRAPWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_BOOTSTRAPWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BootstrapWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BootstrapWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BootstrapWebPlatform();
