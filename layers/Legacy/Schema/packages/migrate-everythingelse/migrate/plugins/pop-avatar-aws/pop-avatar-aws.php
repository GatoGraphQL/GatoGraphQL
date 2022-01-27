<?php
/*
Plugin Name: PoP Avatar for AWS
Version: 0.1
Description: Use AWS for the Avatar for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_AVATAR_AWS_VERSION', 0.106);
define('POP_AVATAR_AWS_DIR', dirname(__FILE__));
define('POP_AVATAR_AWS_ORIGINURI', plugins_url('', __FILE__));

class PoP_Avatar_AWS
{
    public function __construct()
    {

        // Priority: after PoP Avatar
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888320);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_AVATAR_AWS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Avatar_AWS_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Avatar_AWS_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Avatar_AWS();
