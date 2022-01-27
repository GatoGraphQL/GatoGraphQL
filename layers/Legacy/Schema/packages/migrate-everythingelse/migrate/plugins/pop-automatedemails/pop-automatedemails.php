<?php
/*
Plugin Name: PoP Automated Emails
Version: 0.1
Description: Library for sending automated emails for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_AUTOMATEDEMAILS_VERSION', 0.107);
define('POP_AUTOMATEDEMAILS_DIR', dirname(__FILE__));

class PoP_AutomatedEmails
{
    public function __construct()
    {

        // Priority: after PoP Server-Side Rendering
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888412);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_AUTOMATEDEMAILS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AutomatedEmails_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AutomatedEmails_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AutomatedEmails();
