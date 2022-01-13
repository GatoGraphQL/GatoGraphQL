<?php
/*
Plugin Name: PoP Email Sender
Version: 0.1
Description: Utilities for sending emails for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EMAILSENDER_VERSION', 0.108);
define('POP_EMAILSENDER_DIR', dirname(__FILE__));
define('POP_EMAILSENDER_DIR_RESOURCES', POP_EMAILSENDER_DIR.'/resources');

class PoP_EmailSender
{
    public function __construct()
    {

        // Priority: after PoP Application
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_EMAILSENDER_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EmailSender_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EmailSender_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EmailSender();
