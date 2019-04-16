<?php
/*
Plugin Name: PoP Taxonomy for WordPress
Version: 1.0
Description: Implementation of WordPress functions for PoP CMS
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\Taxonomy\WP;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TAXONOMYWP_VERSION', 0.106);
define('POP_TAXONOMYWP_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\CMS\HooksAPI_Factory::getInstance()->addFilter(
            'PoP_Taxonomy_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );
        
        // Priority: mid section, after PoP CMS Model WP
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'init'), 255);
    }
    public function getProviderValidationClass($class)
    {
        return Validation::class;
    }
    
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_TAXONOMYWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
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
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
    new Plugin();
}
