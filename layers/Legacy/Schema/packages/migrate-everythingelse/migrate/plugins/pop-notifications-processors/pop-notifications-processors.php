<?php
/*
Plugin Name: PoP Notifications Processors
Version: 0.1
Description: The foundation for a PoP Notifications Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NOTIFICATIONSPROCESSORS_VERSION', 0.106);
define('POP_NOTIFICATIONSPROCESSORS_DIR', dirname(__FILE__));

class PoP_NotificationsProcessors
{
    public function __construct()
    {

        // Priority: after PoP User Platform Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888840);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_NOTIFICATIONSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NotificationsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NotificationsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NotificationsProcessors();
