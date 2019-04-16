<?php
/*
Plugin Name: PoP Taxonomy Query
Version: 1.0
Description: The foundation for a PoP Taxonomy Query
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\TaxonomyQuery;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TAXONOMYQUERY_VERSION', 0.106);
define('POP_TAXONOMYQUERY_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {
        
        // Priority: new section, after PoP CMS Model and PoP Taxonomy
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'init'), 210);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_TAXONOMYQUERY_INITIALIZED', true);
        }
    }
    public function validate()
    {
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
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
    new Plugins();
}
