<?php
/*
Plugin Name: PoP Content Posts Processors
Description: Implementation of Content Posts Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTSPROCESSORS_VERSION', 0.132);
define('POP_LOCATIONPOSTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_LocationPostsProcessors
{
    public function __construct()
    {

        // // Priority: after PoP Locations Processors
        // \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888850);
        // Priority: after PoP Locations Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888890);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsProcessors();
