<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Comments
Description: Implementation of Add Comments for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOMMENTS_VERSION', 0.132);
define('POP_ADDCOMMENTS_DIR', dirname(__FILE__));

class PoP_AddComments
{
    public function __construct()
    {

        // Priority: after PoP Notifications
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function init()
    {
        define('POP_ADDCOMMENTS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOMMENTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddComments_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddComments_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddComments();
