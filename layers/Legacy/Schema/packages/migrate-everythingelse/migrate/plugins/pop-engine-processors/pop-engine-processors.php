<?php
/*
Plugin Name: PoP Engine Processors
Version: 0.1
Description: Collection of processors for PoP Engine
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEPROCESSORS_VERSION', 0.229);
define('POP_ENGINEPROCESSORS_DIR', dirname(__FILE__));

class PoP_EngineProcessors
{
    public function __construct()
    {

        // Priority: new section, after PoP Application Web Platform section
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888600);
    }
    public function init()
    {
        define('POP_ENGINEPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINEPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EngineProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EngineProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EngineProcessors();
