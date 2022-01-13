<?php
/*
Plugin Name: PoP Email Sender for AWS
Version: 0.1
Description: Use AWS for Sending emails for the Platform of Platforms (PoP). It uses a combination of S3, Lambda and SES to send the emails in an asynchronous way.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EMAILSENDER_AWS_VERSION', 0.107);
define('POP_EMAILSENDER_AWS_DIR', dirname(__FILE__));
define('POP_EMAILSENDER_AWS_DIR_RESOURCES', POP_EMAILSENDER_AWS_DIR.'/resources');

class PoP_EmailSender_AWS
{
    public function __construct()
    {

        // Priority: after PoP Email Sender
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888320);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_EMAILSENDER_AWS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        require_once 'validation.php';
        $validation = new PoP_EmailSender_AWS_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        require_once 'initialization.php';
        $initialization = new PoP_EmailSender_AWS_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EmailSender_AWS();
