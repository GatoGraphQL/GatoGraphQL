<?php
/*
Plugin Name: PoP User Stance Processors
Description: Implementation of User Stance Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERSTANCEPROCESSORS_VERSION', 0.132);
define('POP_USERSTANCEPROCESSORS_DIR', dirname(__FILE__));

class PoP_UserStanceProcessors
{
    public function __construct()
    {

        // Priority: after PoP Content Creation Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888860);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_USERSTANCEPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserStanceProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserStanceProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserStanceProcessors();
