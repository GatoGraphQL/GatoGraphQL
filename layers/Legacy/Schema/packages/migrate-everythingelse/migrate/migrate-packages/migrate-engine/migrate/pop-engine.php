<?php
/*
Plugin Name: PoP Engine
Version: 0.1
Description: The Platform of Platforms is a niche Social Media website. It can operate as a Platform, it can aggregate other Platforms, or it can be a combination.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\Engine;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINE_VERSION', 0.108);
define('POP_ENGINE_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        // Allow the Theme to override definitions.
        // Priority: new section, after PoP CMS section
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 88823);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINE_INITIALIZED', true);

            // Allow plug-ins to override values
            \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'defineConstants'), 888110);
        }
    }
    public function defineConstants()
    {
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $content_dir = $cmsengineapi->getContentDir();
        $content_url = $cmsengineapi->getContentURL();
        define('POP_CONTENT_DIR', $content_dir.'/pop-content');
        define('POP_CONTENT_URL', $content_dir.'/pop-content');
        define('POP_BUILD_DIR', $content_dir.'/pop-build');
        define('POP_GENERATECACHE_DIR', $content_dir.'/pop-generatecache');
        // define('POP_CACHE_DIR', $content_dir.'/pop-cache');
        define('POP_RUNTIMECONTENT_DIR', $content_dir.'/pop-runtimecontent');
        define('POP_RUNTIMECONTENT_URL', $content_url.'/pop-runtimecontent');
    }
    public function validate()
    {
        return true;
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
new Plugin();
