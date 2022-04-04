<?php
/*
Plugin Name: PoP User Login Processors
Description: Implementation of User Login Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERLOGINPROCESSORS_VERSION', 0.132);
define('POP_USERLOGINPROCESSORS_DIR', dirname(__FILE__));
define('POP_USERLOGINPROCESSORS_PHPTEMPLATES_DIR', POP_USERLOGINPROCESSORS_DIR.'/php-templates/compiled');

class PoP_UserLoginProcessors
{
    public function __construct()
    {

        // Priority: after PoP Email Sender Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888820);
    }
    public function init()
    {
        define('POP_USERLOGINPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERLOGINPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserLoginProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserLoginProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserLoginProcessors();
