<?php
/*
Plugin Name: PoP User Avatar
Version: 0.1
Description: Implementation of the User Avatar plugin for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERAVATAR_VERSION', 0.110);
define('POP_USERAVATAR_DIR', dirname(__FILE__));
define('POP_USERAVATAR_ORIGINURI', plugins_url('', __FILE__));

class PoP_UserAvatar
{
    public function __construct()
    {

        // Priority: after PoP Notifications
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function init()
    {
        define('POP_USERAVATAR_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERAVATAR_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserAvatar_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserAvatar_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserAvatar();
