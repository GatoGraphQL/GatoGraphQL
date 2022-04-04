<?php
/*
Plugin Name: PoP Social Network Web Platform
Description: Implementation of Social Network Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SOCIALNETWORKWEBPLATFORM_VERSION', 0.132);
define('POP_SOCIALNETWORKWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_SOCIALNETWORKWEBPLATFORM_PHPTEMPLATES_DIR', POP_SOCIALNETWORKWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_SocialNetworkWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888560);
    }
    public function init()
    {
        define('POP_SOCIALNETWORKWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALNETWORKWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialNetworkWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialNetworkWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialNetworkWebPlatform();
