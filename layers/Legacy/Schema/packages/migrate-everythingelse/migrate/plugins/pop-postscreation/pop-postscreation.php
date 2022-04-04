<?php
/*
Plugin Name: PoP Posts Creation
Version: 0.1
Description: The foundation for a PoP Posts Creation
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_POSTSCREATION_VERSION', 0.106);
define('POP_POSTSCREATION_DIR', dirname(__FILE__));

class PoP_PostsCreation
{
    public function __construct()
    {

        // Priority: after PoP Related Posts
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888350);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_POSTSCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_PostsCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PostsCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PostsCreation();
