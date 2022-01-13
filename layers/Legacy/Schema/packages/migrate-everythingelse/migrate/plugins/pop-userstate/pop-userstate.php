<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User State
Description: Implementation of User State for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERSTATE_VERSION', 0.132);
define('POP_USERSTATE_DIR', dirname(__FILE__));

class PoP_UserState
{
    public function __construct()
    {

        // Priority: after PoP Engine
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888110);
    }
    public function init()
    {
        define('POP_USERSTATE_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERSTATE_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserState_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserState_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserState();
