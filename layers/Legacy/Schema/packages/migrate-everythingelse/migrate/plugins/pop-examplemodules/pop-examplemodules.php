<?php
/*
Plugin Name: PoP Example Modules
Version: 0.1
Description: The foundation for a PoP Example Modules
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
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
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888310);
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
new Plugin();
