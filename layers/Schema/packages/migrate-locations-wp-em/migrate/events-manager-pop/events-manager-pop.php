<?php
/*
Plugin Name: Events Manager for PoP
Version: 0.1
Description: Events Manager for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

define('EMPOP_VERSION', 0.107);
define('EMPOP_DIR', dirname(__FILE__));

class EM_PoP
{
    public function __construct()
    {

        // Priority: after PoP Application
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 310);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('EMPOP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new EM_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new EM_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new EM_PoP();
