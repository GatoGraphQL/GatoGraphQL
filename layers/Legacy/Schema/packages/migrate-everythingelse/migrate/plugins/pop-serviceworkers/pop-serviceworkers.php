<?php
/*
Plugin Name: PoP Service Workers
Description: Implementation of Service Workers for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SERVICEWORKERS_VERSION', 0.132);
define('POP_SERVICEWORKERS_DIR', dirname(__FILE__));
define('POP_SERVICEWORKERS_ASSETS_DIR', POP_SERVICEWORKERS_DIR.'/kernel/serviceworkers/assets');

class PoP_ServiceWorkers
{
    public function __construct()
    {

        // Priority: after PoP Multidomain
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888430);
        // \PoP\Root\App::addAction('PoP:system-generate', array($this,'systemGenerate'));
    }
    public function init()
    {
        define('POP_SERVICEWORKERS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SERVICEWORKERS_INITIALIZED', true);

            // Place after the initialize, so that pop-cluster/ can define it before
            // Allow the Theme to override the cache folder (eg: to add a custom folder after ir, eg: pop-cache/mesym/)
            $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
            if (!defined('POP_SERVICEWORKERS_ASSETDESTINATION_DIR')) {
                define('POP_SERVICEWORKERS_ASSETDESTINATION_DIR', $cmsengineapi->getContentDir().'/pop-serviceworkers');
            }
            if (!defined('POP_SERVICEWORKERS_ASSETDESTINATION_URL')) {
                define('POP_SERVICEWORKERS_ASSETDESTINATION_URL', $cmsengineapi->getContentURL().'/pop-serviceworkers');
            }
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ServiceWorkers_Validation();
        return $validation->validate();
    }
    // function systemGenerate(){

    //     require_once 'installation.php';
    //     $installation = new PoP_ServiceWorkers_Installation();
    //     return $installation->systemGenerate();
    // }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ServiceWorkers_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers();
