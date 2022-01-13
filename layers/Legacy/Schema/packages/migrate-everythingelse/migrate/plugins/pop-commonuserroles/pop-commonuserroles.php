<?php
/*
Plugin Name: PoP Common User Roles
Version: 0.1
Description: The foundation for a PoP Common User Roles
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONUSERROLES_VERSION', 0.106);
define('POP_COMMONUSERROLES_DIR', dirname(__FILE__));

class PoP_CommonUserRoles
{
    public function __construct()
    {

        // Priority: after PoP User Communities
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888380);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONUSERROLES_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonUserRoles_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonUserRoles_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRoles();
