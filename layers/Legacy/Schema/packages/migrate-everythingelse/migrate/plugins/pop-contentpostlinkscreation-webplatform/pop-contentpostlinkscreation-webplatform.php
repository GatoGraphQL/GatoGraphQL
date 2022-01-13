<?php
/*
Plugin Name: PoP Content Post Links Creation Web Platform
Description: Implementation of Content Post Links Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTPOSTLINKSCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_CONTENTPOSTLINKSCREATIONWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_ContentPostLinksCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888560);
    }
    public function init()
    {
        define('POP_CONTENTPOSTLINKSCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTPOSTLINKSCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentPostLinksCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentPostLinksCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinksCreationWebPlatform();
