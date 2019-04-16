<?php
/*
Plugin Name: PoP Example Modules
Version: 1.0
Description: The foundation for a PoP Example Modules
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/
namespace PoP\ExampleModules;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EXAMPLEMODULES_VERSION', 0.106);
define('POP_EXAMPLEMODULES_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        
        // Priority: new section, after PoP Engine section
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'init'), 200);
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('PoP:version', array($this, 'version'), 200);
    }
    public function version($version)
    {
        return POP_EXAMPLEMODULES_VERSION;
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_EXAMPLEMODULES_INITIALIZED', true);
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
    new Plugin();
}
