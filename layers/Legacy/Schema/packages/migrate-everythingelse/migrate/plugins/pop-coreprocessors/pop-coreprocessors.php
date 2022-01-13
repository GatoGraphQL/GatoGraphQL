<?php
/*
Plugin Name: PoP Core Processors
Version: 0.1
Description: Plug-in providing a collection of processors for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COREPROCESSORS_VERSION', 0.230);
define('POP_COREPROCESSORS_VENDORRESOURCESVERSION', 0.200);
define('POP_COREPROCESSORS_DIR', dirname(__FILE__));
define('POP_COREPROCESSORS_PHPTEMPLATES_DIR', POP_COREPROCESSORS_DIR.'/php-templates/compiled');

class PoP_CoreProcessors
{
    public function __construct()
    {

        // Priority: after PoP Bootstrap Collection Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888710);
        // \PoP\Root\App::getHookManager()->addAction('PoP:system-generate', array($this,'systemGenerate'));
    }
    public function init()
    {
        define('POP_COREPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_COREPROCESSORS_INITIALIZED', true);
        }
    }
    // function systemGenerate(){

    //     require_once 'installation.php';
    //     $installation = new PoP_CoreProcessors_Installation();
    //     return $installation->systemGenerate();
    // }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CoreProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CoreProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors();
