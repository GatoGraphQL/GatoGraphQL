<?php
/*
Plugin Name: PoP Add Comments Web Platform
Description: Implementation of Add Comments Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOMMENTSWEBPLATFORM_VERSION', 0.132);
define('POP_ADDCOMMENTSWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_AddCommentsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888560);
    }
    public function init()
    {
        define('POP_ADDCOMMENTSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOMMENTSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddCommentsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddCommentsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddCommentsWebPlatform();
