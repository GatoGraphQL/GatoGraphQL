<?php
/*
Plugin Name: PoP Web Platform Engine for WordPress
Version: 0.1
Description: Implementation of Engine Web Platform for PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\EngineWebPlatform;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEWEBPLATFORMWP_VERSION', 0.108);
define('POP_ENGINEWEBPLATFORMWP_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\Root\App::addFilter(
            'PoP_EngineWebPlatform_Validation:provider-validation-class',
            $this->getProviderValidationClass(...)
        );

        // Priority: after PoP Web Platform Engine, inner circle
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888401);
    }
    public function getProviderValidationClass($class)
    {
        return Validation::class;
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINEWEBPLATFORMWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate(true);
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
new Plugins();
