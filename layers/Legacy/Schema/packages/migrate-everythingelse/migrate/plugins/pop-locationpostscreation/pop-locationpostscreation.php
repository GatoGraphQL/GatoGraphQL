<?php
/*
Plugin Name: PoP Location Posts Creation
Version: 0.1
Description: The foundation for a PoP Location Posts Creation
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTSCREATION_VERSION', 0.106);
define('POP_LOCATIONPOSTSCREATION_DIR', dirname(__FILE__));

class PoP_LocationPostsCreation
{
    public function __construct()
    {

        // Priority: after PoP Location Posts and PoP Posts Creation
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTSCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostsCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostsCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsCreation();
