<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Contact Us Processors
Description: Implementation of Contact Us Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTACTUSPROCESSORS_VERSION', 0.132);
define('POP_CONTACTUSPROCESSORS_DIR', dirname(__FILE__));
define('POP_CONTACTUSPROCESSORS_PHPTEMPLATES_DIR', POP_CONTACTUSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_ContactUsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Forms Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888820);
    }
    public function init()
    {
        define('POP_CONTACTUSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTACTUSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContactUsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContactUsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContactUsProcessors();
