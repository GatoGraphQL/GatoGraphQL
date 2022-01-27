<?php
/*
Plugin Name: PoP Edit Users
Version: 0.1
Description: The foundation for a PoP Edit Users
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\EditUsers;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EDITUSERS_VERSION', 0.106);
define('POP_EDITUSERS_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: new section, after PoP Users
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888205);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_EDITUSERS_INITIALIZED', true);
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
