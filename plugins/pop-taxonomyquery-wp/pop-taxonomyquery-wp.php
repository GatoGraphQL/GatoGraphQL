<?php
/*
Plugin Name: PoP Taxonomy Query for WordPress
Version: 1.0
Description: Implementation of WordPress functions for PoP CMS
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\TaxonomyQuery\WP;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TAXONOMYQUERYWP_VERSION', 0.106);
define('POP_TAXONOMYQUERYWP_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\CMS\HooksAPI_Factory::getInstance()->addFilter(
            'PoP_TaxonomyQuery_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );
        
        // Priority: mid section, after PoP CMS Model WP and PoP TaxonomyQuery WP
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'init'), 260);
    }
    public function getProviderValidationClass($class)
    {
        return Validation::class;
    }
    
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_TAXONOMYQUERYWP_INITIALIZED', true);
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
