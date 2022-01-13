<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User Stance
Description: Implementation of User Stance for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERSTANCE_VERSION', 0.132);
define('POP_USERSTANCE_DIR', dirname(__FILE__));

class PoP_UserStance
{
    public function __construct()
    {

        // Priority: after PoP Add Related Posts
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888370);
    }
    public function init()
    {
        define('POP_USERSTANCE_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERSTANCE_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserStance_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserStance_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserStance();
