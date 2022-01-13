<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Forms Web Platform
Description: Implementation of Forms Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_FORMSWEBPLATFORM_VERSION', 0.132);
define('POP_FORMSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_FORMSWEBPLATFORM_PHPTEMPLATES_DIR', POP_FORMSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_FormsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Captcha
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888520);
    }
    public function init()
    {
        define('POP_FORMSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_FORMSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_FormsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_FormsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_FormsWebPlatform();
