<?php
/*
Plugin Name: PoP Bootstrap Processors
Version: 0.1
Description: Plug-in providing a collection of processors for Bootstrap for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BOOTSTRAPPROCESSORS_VERSION', 0.216);

class PoP_BootstrapProcessors
{
    public function __construct()
    {

        // Priority: after PoP Base Collection Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888620);
    }
    public function init()
    {
        define('POP_BOOTSTRAPPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_BOOTSTRAPPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BootstrapProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BootstrapProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BootstrapProcessors();
