<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User Stance WordPress
Description: Implementation of User Stance for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERSTANCEWP_VERSION', 0.132);
define('POP_USERSTANCEWP_DIR', dirname(__FILE__));

define('POP_USERSTANCEWP__FILE__', __FILE__);
define('POP_USERSTANCEWP_BASE', plugin_basename(POP_USERSTANCEWP__FILE__));

class PoP_UserStanceWP
{
    public function __construct()
    {

        // Priority: after PoP Add Related Posts
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888372);
    }
    public function init()
    {
        define('POP_USERSTANCEWP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERSTANCEWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserStanceWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserStanceWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserStanceWP();
