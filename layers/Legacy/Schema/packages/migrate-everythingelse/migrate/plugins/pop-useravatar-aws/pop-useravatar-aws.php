<?php
/*
Plugin Name: PoP User Avatar for AWS
Version: 0.1
Description: Use AWS for the User Avatar for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERAVATAR_AWS_VERSION', 0.106);
define('POP_USERAVATAR_AWS_DIR', dirname(__FILE__));
define('POP_USERAVATAR_AWS_ORIGINURI', plugins_url('', __FILE__));

class PoP_UserAvatar_AWS
{
    public function __construct()
    {

        // Priority: after PoP User Avatar
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_USERAVATAR_AWS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserAvatar_AWS_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserAvatar_AWS_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserAvatar_AWS();
