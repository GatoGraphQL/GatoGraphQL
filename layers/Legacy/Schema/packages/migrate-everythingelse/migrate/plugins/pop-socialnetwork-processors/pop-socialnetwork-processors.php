<?php
/*
Plugin Name: PoP Social Network Processors
Description: Implementation of Social Network Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SOCIALNETWORKPROCESSORS_VERSION', 0.132);
define('POP_SOCIALNETWORKPROCESSORS_DIR', dirname(__FILE__));

class PoP_SocialNetworkProcessors
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888860);
    }
    public function init()
    {
        define('POP_SOCIALNETWORKPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALNETWORKPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialNetworkProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialNetworkProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialNetworkProcessors();
