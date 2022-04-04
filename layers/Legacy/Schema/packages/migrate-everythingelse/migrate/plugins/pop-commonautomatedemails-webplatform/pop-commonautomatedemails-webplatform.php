<?php
/*
Plugin Name: Common Automated Emails for PoP Web Platform
Version: 0.1
Description: Implementations of automated emails for PoP Web Platform
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONAUTOMATEDEMAILSWEBPLATFORM_VERSION', 0.108);
define('POP_COMMONAUTOMATEDEMAILSWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_CommonAutomatedEmailsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Notifications Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888550);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONAUTOMATEDEMAILSWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonAutomatedEmailsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonAutomatedEmailsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new PoP_CommonAutomatedEmailsWebPlatform();
