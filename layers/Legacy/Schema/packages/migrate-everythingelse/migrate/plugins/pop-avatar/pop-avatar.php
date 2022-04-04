<?php
/*
Plugin Name: PoP Avatar
Version: 0.1
Description: Implementation of the Avatar plugin for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_AVATAR_VERSION', 0.110);
define('POP_AVATAR_DIR', dirname(__FILE__));
define('POP_AVATAR_ORIGINURI', plugins_url('', __FILE__));

class PoP_Avatar
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888310);
    }
    public function init()
    {
        define('POP_AVATAR_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_AVATAR_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Avatar_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Avatar_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Avatar();
