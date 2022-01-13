<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: Google XML Sitemaps for PoP
Description: Integration of plugin Google XML Sitemaps with PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_GSG_VERSION', 0.126);
define('POP_GSG_DIR', dirname(__FILE__));

class PoP_GSG
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_GSG_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('GoogleSitemapGeneratorLoader');
        return true;
        include_once 'validation.php';
        $validation = new PoP_GSG_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_GSG_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_GSG();
