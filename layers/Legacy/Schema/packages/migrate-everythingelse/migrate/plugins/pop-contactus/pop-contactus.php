<?php
/*
Plugin Name: PoP Contact Us
Description: Implementation of Contact Us for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTACTUS_VERSION', 0.132);
define('POP_CONTACTUS_DIR', dirname(__FILE__));

class PoP_ContactUs
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888310);
    }
    public function init()
    {
        define('POP_CONTACTUS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTACTUS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContactUs_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContactUs_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContactUs();
