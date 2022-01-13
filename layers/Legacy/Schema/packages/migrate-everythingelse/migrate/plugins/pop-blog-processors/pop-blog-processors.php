<?php
/*
Plugin Name: PoP Blog Processors
Description: Implementation of Blog Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BLOGPROCESSORS_VERSION', 0.132);
define('POP_BLOGPROCESSORS_DIR', dirname(__FILE__));

class PoP_BlogProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888810);
    }
    public function init()
    {
        define('POP_BLOGPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_BLOGPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BlogProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BlogProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BlogProcessors();
