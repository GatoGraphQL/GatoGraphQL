<?php
/*
Plugin Name: PoP User Roles
Version: 0.1
Description: The foundation for a PoP User Roles
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\UserRoles;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERROLES_VERSION', 0.106);
define('POP_USERROLES_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: new section, after PoP Users
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888205);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_USERROLES_INITIALIZED', true);
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
