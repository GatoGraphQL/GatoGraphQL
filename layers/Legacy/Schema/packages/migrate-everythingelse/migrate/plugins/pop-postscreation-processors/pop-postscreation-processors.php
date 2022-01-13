<?php
/*
Plugin Name: PoP Posts Creation Processors
Description: Implementation of Posts Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_POSTSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_POSTSCREATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_PostsCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Content Creation Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888850);
    }
    public function init()
    {
        define('POP_POSTSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_POSTSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_PostsCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PostsCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PostsCreationProcessors();
