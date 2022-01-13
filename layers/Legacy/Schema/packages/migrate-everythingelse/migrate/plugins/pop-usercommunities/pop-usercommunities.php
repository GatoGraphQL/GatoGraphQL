<?php
/*
Plugin Name: PoP User Communities
Version: 0.1
Description: Implementation of PoP User Communities
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERCOMMUNITIES_VERSION', 0.106);
define('POP_USERCOMMUNITIES_DIR', dirname(__FILE__));

class PoP_UserCommunities
{
    public function __construct()
    {

        // Priority: after PoP Social Network
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888370);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_USERCOMMUNITIES_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserCommunities_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserCommunities_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserCommunities();
