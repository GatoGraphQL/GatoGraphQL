<?php
/*
Plugin Name: PoP Avatar Processors
Version: 0.1
Description: Implementation of processors for the Avatar for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_AVATARPROCESSORS_VERSION', 0.111);
define('POP_AVATARPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('POP_AVATARPROCESSORS_DIR', dirname(__FILE__));

class PoP_AvatarProcessors
{
    public function __construct()
    {

        // Priority: after PoP Base Collection Processors (even though it doesn't depend on it)
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888620);
    }
    public function init()
    {
        define('POP_AVATARPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_AVATARPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AvatarProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AvatarProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AvatarProcessors();
