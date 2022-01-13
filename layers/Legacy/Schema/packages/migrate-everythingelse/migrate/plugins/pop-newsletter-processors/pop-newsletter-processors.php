<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Newsletter Processors
Description: Implementation of Newsletter Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NEWSLETTERPROCESSORS_VERSION', 0.132);
define('POP_NEWSLETTERPROCESSORS_DIR', dirname(__FILE__));

class PoP_NewsletterProcessors
{
    public function __construct()
    {

        // Priority: after PoP User Platform Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888840);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_NEWSLETTERPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NewsletterProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NewsletterProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NewsletterProcessors();
