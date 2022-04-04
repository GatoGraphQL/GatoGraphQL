<?php
/*
Plugin Name: PoP Theme for WordPress
Version: 0.1
Description: Implementation of Theme for PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\Theme;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_THEMEWP_VERSION', 0.108);
define('POP_THEMEWP_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\Root\App::addFilter(
            'PoP_Theme_Validation:provider-validation-class',
            $this->getProviderValidationClass(...)
        );

        // Priority: after PoP Theme, inner circle
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
            define('POP_THEMEWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
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
