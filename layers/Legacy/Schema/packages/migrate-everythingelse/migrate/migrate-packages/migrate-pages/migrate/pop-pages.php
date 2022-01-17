<?php
/*
Plugin Name: PoP Pages
Version: 0.1
Description: The foundation for a PoP Pages
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPCMSSchema\Pages;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_PAGES_VERSION', 0.106);
define('POP_PAGES_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: new section, after PoP Engine section
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888200);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_PAGES_INITIALIZED', true);
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
