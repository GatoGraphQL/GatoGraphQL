<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP CDN
Description: Implementation of the CDN for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CDN_VERSION', 0.157);
define('POP_CDN_DIR', dirname(__FILE__));
define('POP_CDN_ASSETS_DIR', POP_CDN_DIR.'/library/cdn/assets');

class PoP_CDN
{
    public function __construct()
    {

        // Priority: after PoP Engine Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888410);
        // \PoP\Root\App::getHookManager()->addAction('PoP:system-generate', array($this,'systemGenerate'));
    }
    public function init()
    {
        define('POP_CDN_URL', plugins_url('', __FILE__));

        define('POP_CDN_ASSETDESTINATION_DIR', POP_CONTENT_DIR.'/cdn');
        define('POP_CDN_ASSETDESTINATION_URL', POP_CONTENT_URL.'/cdn');

        if ($this->validate()) {
            $this->initialize();
            define('POP_CDN_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CDN_Validation();
        return $validation->validate();
    }
    // function systemGenerate(){

    //     require_once 'installation.php';
    //     $installation = new PoP_CDN_Installation();
    //     return $installation->systemGenerate();
    // }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CDN_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CDN();
