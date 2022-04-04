<?php
/*
Plugin Name: PoP Posts Creation Web Platform
Description: Implementation of Posts Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_POSTSCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_POSTSCREATIONWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_PostsCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888560);
    }
    public function init()
    {
        define('POP_POSTSCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_POSTSCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_PostsCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PostsCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PostsCreationWebPlatform();
