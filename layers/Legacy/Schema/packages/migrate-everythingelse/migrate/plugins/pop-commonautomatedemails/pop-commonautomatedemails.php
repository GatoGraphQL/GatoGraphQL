<?php
/*
Plugin Name: Common Automated Emails for PoP
Version: 0.1
Description: Implementations of automated emails for PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONAUTOMATEDEMAILS_VERSION', 0.108);
define('POP_COMMONAUTOMATEDEMAILS_DIR', dirname(__FILE__));

class PoP_CommonAutomatedEmails
{
    public function __construct()
    {

        // Priority: after PoP Automated Emails
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888420);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONAUTOMATEDEMAILS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonAutomatedEmails_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonAutomatedEmails_Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new PoP_CommonAutomatedEmails();
