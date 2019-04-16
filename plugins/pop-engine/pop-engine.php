<?php
/*
Plugin Name: PoP Engine
Version: 1.0
Description: The Platform of Platforms is a niche Social Media website. It can operate as a Platform, it can aggregate other Platforms, or it can be a combination.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\Engine;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINE_VERSION', 0.108);
define('POP_ENGINE_DIR', dirname(__FILE__));
define('POP_ENGINE_TEMPLATES', POP_ENGINE_DIR.'/templates');

class Plugin
{
    public function __construct()
    {
        
        // Allow the Theme to override definitions.
        // Priority: new section, after PoP CMS section
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'init'), 100);
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'defineStartupConstants'), PHP_INT_MAX);
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('PoP:version', array($this, 'version'), 100);
    }
    public function version($version)
    {
        return POP_ENGINE_VERSION;
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINE_INITIALIZED', true);

            // Allow plug-ins to override values
            \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'defineConstants'), 110);
        }
    }
    public function defineStartupConstants()
    {
        define('POP_STARTUP_INITIALIZED', true);
    }
    public function defineConstants()
    {
        define('POP_CONTENT_DIR', WP_CONTENT_DIR.'/pop-content');
        define('POP_CONTENT_URL', WP_CONTENT_URL.'/pop-content');
        
        define('POP_BUILD_DIR', WP_CONTENT_DIR.'/pop-build');
        define('POP_GENERATECACHE_DIR', WP_CONTENT_DIR.'/pop-generatecache');
    }
    public function validate()
    {
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
    new Plugin();
}
