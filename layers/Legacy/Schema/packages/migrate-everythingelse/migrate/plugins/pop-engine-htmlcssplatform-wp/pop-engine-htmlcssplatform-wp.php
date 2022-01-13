<?php
/*
Plugin Name: PoP HTML/CSS Platform Engine for WordPress
Version: 0.1
Description: Implementation of Engine HTML/CSS Platform for PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\EngineHTMLCSSPlatform;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEHTMLCSSPLATFORMWP_VERSION', 0.108);
define('POP_ENGINEHTMLCSSPLATFORMWP_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\Root\App::addFilter(
            'PoP_EngineHTMLCSSPlatform_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP HTML/CSS Platform Engine, inner circle
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888401);
    }
    public function getProviderValidationClass($class)
    {
        return Validation::class;
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINEHTMLCSSPLATFORMWP_INITIALIZED', true);
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
