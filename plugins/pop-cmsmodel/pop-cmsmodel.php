<?php
/*
Plugin Name: PoP CMS Model
Version: 1.0
Description: The foundation for a PoP CMS Model
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\CMSModel;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CMSMODEL_VERSION', 0.106);
define('POP_CMSMODEL_DIR', dirname(__FILE__));
define('POP_CMSMODEL_LIB', POP_CMSMODEL_DIR.'/library');

class Plugins
{
    public function __construct()
    {
        
        // Priority: new section, after PoP Engine section
        add_action('plugins_loaded', array($this, 'init'), 200);
        add_action('PoP:version', array($this, 'version'), 200);
    }
    public function version($version)
    {
        return POP_CMSMODEL_VERSION;
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CMSMODEL_INITIALIZED', true);
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
