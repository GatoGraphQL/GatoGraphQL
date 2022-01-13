<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Content Creation
Description: Implementation of Content Creation for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTCREATION_VERSION', 0.132);
define('POP_CONTENTCREATION_DIR', dirname(__FILE__));

class PoP_ContentCreation
{
    public function __construct()
    {

        // Priority: after PoP Notifications
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function init()
    {
        define('POP_CONTENTCREATION_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentCreation();
