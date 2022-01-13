<?php
/*
Plugin Name: Common Automated Emails for PoP Processors
Version: 0.1
Description: Implementations of automated emails for PoP Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONAUTOMATEDEMAILSPROCESSORS_VERSION', 0.108);
define('POP_COMMONAUTOMATEDEMAILSPROCESSORS_DIR', dirname(__FILE__));

class PoP_CommonAutomatedEmailsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888890);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONAUTOMATEDEMAILSPROCESSORS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonAutomatedEmailsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonAutomatedEmailsProcessors_Initialization();
        return $initialization->initialize();
    }
}
/**
 * Initialization
 */
new PoP_CommonAutomatedEmailsProcessors();
