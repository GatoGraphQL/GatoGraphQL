<?php
/*
Plugin Name: PoP Common User Roles Processors
Version: 0.1
Description: Implementation of PoP Common User Roles Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONUSERROLESPROCESSORS_VERSION', 0.106);
define('POP_COMMONUSERROLESPROCESSORS_DIR', dirname(__FILE__));

class PoP_CommonUserRolesProcessors
{
    public function __construct()
    {

        // Priority: after PoP User Communities Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888880);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONUSERROLESPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonUserRolesProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonUserRolesProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRolesProcessors();
