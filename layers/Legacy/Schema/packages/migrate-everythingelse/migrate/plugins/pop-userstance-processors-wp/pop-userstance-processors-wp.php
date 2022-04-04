<?php
/*
Plugin Name: PoP User Stance Processors WordPress
Description: Implementation of User Stance Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERSTANCEPROCESSORSWP_VERSION', 0.132);
define('POP_USERSTANCEPROCESSORSWP_DIR', dirname(__FILE__));

class PoP_UserStanceProcessorsWP
{
    public function __construct()
    {
        // Priority: after PoP Content Creation Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888862);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_USERSTANCEPROCESSORSWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserStanceProcessorsWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserStanceProcessorsWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserStanceProcessorsWP();
