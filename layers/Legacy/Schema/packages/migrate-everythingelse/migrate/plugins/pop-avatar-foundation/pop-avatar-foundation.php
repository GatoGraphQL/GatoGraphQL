<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Avatar Foundation
Description: Implementation of Avatar Foundation for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_AVATARFOUNDATION_VERSION', 0.132);
define('POP_AVATARFOUNDATION_DIR', dirname(__FILE__));

class PoP_AvatarFoundation
{
    public function __construct()
    {

        // Priority: after PoP Engine
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888110);
    }
    public function init()
    {
        define('POP_AVATARFOUNDATION_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_AVATARFOUNDATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AvatarFoundation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AvatarFoundation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AvatarFoundation();
