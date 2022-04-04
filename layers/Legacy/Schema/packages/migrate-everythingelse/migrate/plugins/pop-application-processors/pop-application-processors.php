<?php
/*
Plugin Name: PoP Application Processors
Version: 0.1
Description: Collection of processors for PoP Application
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_APPLICATIONPROCESSORS_VERSION', 0.229);
define('POP_APPLICATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_ApplicationProcessors
{
    public function __construct()
    {

        // Priority: new section, after PoP Master Collection Processors section
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888800);
    }
    public function init()
    {
        define('POP_APPLICATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_APPLICATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ApplicationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ApplicationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ApplicationProcessors();
