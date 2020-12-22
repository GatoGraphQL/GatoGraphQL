<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/*
Plugin Name: PoP Captcha
Description: Implementation of Captcha for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CAPTCHA_VERSION', 0.132);
define('POP_CAPTCHA_DIR', dirname(__FILE__));

class PoP_Captcha
{
    public function __construct()
    {

        // Priority: after PoP Application
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 304);
    }
    public function init()
    {
        define('POP_CAPTCHA_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CAPTCHA_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Captcha_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Captcha_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Captcha();
