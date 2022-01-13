<?php
/*
Plugin Name: PoP Notifications
Version: 0.1
Description: The foundation for a PoP Notifications
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NOTIFICATIONS_VERSION', 0.106);
define('POP_NOTIFICATIONS_DIR', dirname(__FILE__));

class PoP_Notifications
{
    public function __construct()
    {

        // Priority: after PoP User Platform
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888340);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_NOTIFICATIONS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Notifications_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Notifications_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Notifications();
