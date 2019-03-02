<?php
/*
Plugin Name: PoP Engine for WordPress
Version: 1.0
Description: Implementation of Module Definitions for PoP modules
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
Author URI: https://getpop.org/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEWP_VERSION', 0.108);
define('POP_ENGINEWP_DIR', dirname(__FILE__));

class PoP_EngineWP
{
    public function __construct()
    {

        // Priority: after PoP Engine, inner circle
        \PoP\CMS\HooksAPI_Factory::getInstance()->addAction('plugins_loaded', array($this, 'init'), 102);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINEWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        include_once 'validation.php';
        $validation = new PoP_EngineWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EngineWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
if (!defined('POP_SERVER_DISABLEPOP') || !POP_SERVER_DISABLEPOP) {
    new PoP_EngineWP();
}
