<?php
/*
Plugin Name: PoP SPA Processors
Version: 0.1
Description: Implementation of processors for the SPA for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SPAPROCESSORS_VERSION', 0.111);
define('POP_SPAPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('POP_SPAPROCESSORS_DIR', dirname(__FILE__));

class PoP_SPAProcessors
{
    public function __construct()
    {

        // Priority: after PoP Base Collection Processors (even though it doesn't depend on it)
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888620);
    }
    public function init()
    {
        define('POP_SPAPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SPAPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SPAProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SPAProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SPAProcessors();
