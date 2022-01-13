<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Contact Us Web Platform
Description: Implementation of Contact Us Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTACTUSWEBPLATFORM_VERSION', 0.132);
define('POP_CONTACTUSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_CONTACTUSWEBPLATFORM_PHPTEMPLATES_DIR', POP_CONTACTUSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_ContactUsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }
    public function init()
    {
        define('POP_CONTACTUSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTACTUSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContactUsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContactUsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContactUsWebPlatform();
