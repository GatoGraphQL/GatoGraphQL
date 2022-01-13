<?php
/*
Plugin Name: PoP Bootstrap Collection Processors
Version: 0.1
Description: Implementation of PoP Bootstrap Collection Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BOOTSTRAPCOLLECTIONPROCESSORS_VERSION', 0.106);
define('POP_BOOTSTRAPCOLLECTIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_BootstrapCollectionProcessors
{
    public function __construct()
    {

        // // Priority: new section, after PoP Base Collection Processors section
        // HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888700);
        // Priority: after PoP Core Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888720);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_BOOTSTRAPCOLLECTIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BootstrapCollectionProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BootstrapCollectionProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BootstrapCollectionProcessors();
