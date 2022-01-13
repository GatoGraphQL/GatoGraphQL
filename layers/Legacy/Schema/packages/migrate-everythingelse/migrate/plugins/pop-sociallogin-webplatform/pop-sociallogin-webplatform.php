<?php
/*
Plugin Name: PoP Social Login Web Platform
Version: 0.1
Description: Collection of Web Platform for Social Login for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_SOCIALLOGINWEBPLATFORM_VERSION', 0.109);
define('POP_SOCIALLOGINWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_SOCIALLOGINWEBPLATFORM_PHPTEMPLATES_DIR', POP_SOCIALLOGINWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_SocialLoginWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Notifications Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }

    public function init()
    {
        define('POP_SOCIALLOGINWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALLOGINWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('wsl_activate');
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialLoginWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialLoginWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialLoginWebPlatform();
