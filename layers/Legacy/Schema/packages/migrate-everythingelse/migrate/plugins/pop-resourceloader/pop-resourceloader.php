<?php
/*
Plugin Name: PoP Resource Loader
Description: Implementation of Resource Loader for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_RESOURCELOADER_VERSION', 0.132);
define('POP_RESOURCELOADER_DIR', dirname(__FILE__));
define('POP_RESOURCELOADER_ASSETS_DIR', POP_RESOURCELOADER_DIR.'/kernel/resourceloaders/config/assets');
define('POP_RESOURCELOADER_VENDORRESOURCESVERSION', 0.100);

class PoP_ResourceLoader
{
    public function __construct()
    {

        // Priority: after PoP Server-Side Rendering, inner circle
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888404);
    }
    public function init()
    {
        define('POP_RESOURCELOADER_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_RESOURCELOADER_INITIALIZED', true);
            \PoP\Root\App::addAction('plugins_loaded', array($this, 'defineConstants'), 888450);
        }
    }

    public function defineConstants()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        define('POP_RESOURCES_DIR', $cmsengineapi->getContentDir().'/pop-resources');
        define('POP_RESOURCES_URL', $cmsengineapi->getContentURL().'/pop-resources');
        define('POP_RESOURCELOADER_BUILD_DIR', POP_BUILD_DIR.'/pop-resourceloader');
        define('POP_RESOURCELOADER_GENERATECACHE_DIR', POP_GENERATECACHE_DIR.'/pop-resourceloader');
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ResourceLoader_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ResourceLoader_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ResourceLoader();
