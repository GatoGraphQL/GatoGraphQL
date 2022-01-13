<?php
/*
Plugin Name: PoP Mailer for AWS
Version: 0.1
Description: Use AWS for Sending emails for the Platform of Platforms (PoP). It uses a combination of S3, Lambda and SES to send the emails in an asynchronous way.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MAILER_AWS_VERSION', 0.107);
define('POP_MAILER_AWS_DIR', dirname(__FILE__));
define('POP_MAILER_AWS_DIR_RESOURCES', POP_MAILER_AWS_DIR.'/resources');

class PoP_Mailer_AWS
{
    public function __construct()
    {

        /**
         * WP Overrides
         */
        include_once dirname(__FILE__).'/wp-includes/load.php';

        // Priority: after PoP AWS
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888120);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_MAILER_AWS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Mailer_AWS_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Mailer_AWS_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Mailer_AWS();
