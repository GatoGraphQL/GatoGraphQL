<?php
/*
Plugin Name: PoP Web Platform Engine Optimizations
Description: Implementation of Web Platform Engine Optimizations for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_VERSION', 0.132);
define('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_DIR', dirname(__FILE__));
define('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_ASSETS_DIR', POP_ENGINEWEBPLATFORMOPTIMIZATIONS_DIR.'/kernel/webplatformengineoptimizationss/config/assets');
define('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_VENDORRESOURCESVERSION', 0.100);

class PoP_WebPlatformEngineOptimizations
{
    public function __construct()
    {

        // Priority: after PoP Resource Loader, inner circle
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888406);
    }
    public function init()
    {
        define('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINEWEBPLATFORMOPTIMIZATIONS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_WebPlatformEngineOptimizations_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_WebPlatformEngineOptimizations_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_WebPlatformEngineOptimizations();
