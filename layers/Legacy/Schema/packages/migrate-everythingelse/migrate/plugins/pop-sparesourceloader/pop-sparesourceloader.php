<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP SPA Resource Loader
Description: Implementation of SPA Resource Loader for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SPARESOURCELOADER_VERSION', 0.132);
define('POP_SPARESOURCELOADER_DIR', dirname(__FILE__));
define('POP_SPARESOURCELOADER_ASSETS_DIR', POP_SPARESOURCELOADER_DIR.'/kernel/resourceloaders/config/assets');
define('POP_SPARESOURCELOADER_VENDORRESOURCESVERSION', 0.100);

class PoP_SPAResourceLoader
{
    public function __construct()
    {

        // Priority: after PoP SPA and PoP Resource Loader, inner circle
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888405);
    }
    public function init()
    {
        define('POP_SPARESOURCELOADER_URL', plugins_url('', __FILE__));
        define('POP_SPARESOURCELOADER_CONTENT_DIR', POP_CONTENT_DIR.'/sparesourceloader');
        define('POP_SPARESOURCELOADER_CONTENT_URL', POP_CONTENT_URL.'/sparesourceloader');

        if ($this->validate()) {
            $this->initialize();
            define('POP_SPARESOURCELOADER_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SPAResourceLoader_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SPAResourceLoader_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SPAResourceLoader();
