<?php
/*
Plugin Name: PoP ApplicationTaxonomies for WordPress
Version: 0.1
Description: Implementation of WordPress functions for PoP CMS
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\ApplicationTaxonomies\WP;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_APPLICATIONTAXONOMIESWP_VERSION', 0.106);
define('POP_APPLICATIONTAXONOMIESWP_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\Root\App::addFilter(
            'PoP_ApplicationTaxonomies_Validation:provider-validation-class',
            $this->getProviderValidationClass(...)
        );

        // Priority: mid section, after PoP Posts WP
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888255);
    }
    public function getProviderValidationClass($class)
    {
        return Validation::class;
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_APPLICATIONTAXONOMIESWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        // require_once 'validation.php';
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
new Plugin();
