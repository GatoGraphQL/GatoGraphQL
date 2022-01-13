<?php
/*
Plugin Name: PoP Location Posts Creation Web Platform
Description: Implementation of LocationPosts Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTSCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_LOCATIONPOSTSCREATIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_LOCATIONPOSTSCREATIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_LOCATIONPOSTSCREATIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_LocationPostsCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Location Posts Web Platform and PoP Posts Creation Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888560);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTSCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTSCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostsCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostsCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsCreationWebPlatform();
