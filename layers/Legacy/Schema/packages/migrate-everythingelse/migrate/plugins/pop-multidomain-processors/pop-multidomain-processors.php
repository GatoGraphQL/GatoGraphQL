<?php
/*
Plugin Name: PoP Multidomain Processors
Description: Implementation of processors for PoP Multidomain
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MULTIDOMAINPROCESSORS_VERSION', 0.161);
define('POP_MULTIDOMAINPROCESSORS_DIR', dirname(__FILE__));

class PoP_MultidomainProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888810);
    }
    public function init()
    {
        define('POP_MULTIDOMAINPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_MULTIDOMAINPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_MultidomainProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MultidomainProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MultidomainProcessors();
