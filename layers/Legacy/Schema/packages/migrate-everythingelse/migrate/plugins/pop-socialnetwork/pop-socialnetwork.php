<?php
/*
Plugin Name: PoP Social Network
Description: Implementation of Social Network for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SOCIALNETWORK_VERSION', 0.132);
define('POP_SOCIALNETWORK_DIR', dirname(__FILE__));

class PoP_SocialNetwork
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function init()
    {
        define('POP_SOCIALNETWORK_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALNETWORK_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialNetwork_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialNetwork_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialNetwork();
