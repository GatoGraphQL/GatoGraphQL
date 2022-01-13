<?php
/*
Plugin Name: PoP User Communities Processors
Version: 0.1
Description: The foundation for a PoP User Communities Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERCOMMUNITIESPROCESSORS_VERSION', 0.106);
define('POP_USERCOMMUNITIESPROCESSORS_DIR', dirname(__FILE__));

class PoP_UserCommunitiesProcessors
{
    public function __construct()
    {

        // Priority: after PoP Social Network Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888870);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_USERCOMMUNITIESPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserCommunitiesProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserCommunitiesProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserCommunitiesProcessors();
