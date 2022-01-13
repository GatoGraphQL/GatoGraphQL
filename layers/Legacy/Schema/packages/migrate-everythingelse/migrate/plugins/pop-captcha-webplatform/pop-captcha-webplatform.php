<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Captcha Web Platform
Description: Implementation of Captcha for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CAPTCHAWEBPLATFORM_VERSION', 0.132);
define('POP_CAPTCHAWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_CAPTCHAWEBPLATFORM_PHPTEMPLATES_DIR', POP_CAPTCHAWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_CaptchaWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }
    public function init()
    {
        define('POP_CAPTCHAWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CAPTCHAWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CaptchaWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CaptchaWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CaptchaWebPlatform();
