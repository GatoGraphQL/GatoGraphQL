<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Location Posts Creation Processors
Description: Implementation of Location Posts Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_LOCATIONPOSTSCREATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_LocationPostsCreationProcessors
{
    public function __construct()
    {

        // // Priority: after PoP Location Posts Processors and PoP Posts Creation Processors
        // \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888860);
        // Priority: after PoP Location Posts Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888900);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostsCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostsCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsCreationProcessors();
