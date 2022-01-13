<?php
/*
Plugin Name: PoP Category Posts Creation Processors
Description: Implementation of Content Category Posts Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CATEGORYPOSTSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_CATEGORYPOSTSCREATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_CategoryPostsCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Posts Creation Processors
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888860);
    }
    public function init()
    {
        define('POP_CATEGORYPOSTSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CATEGORYPOSTSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CategoryPostsCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CategoryPostsCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CategoryPostsCreationProcessors();
