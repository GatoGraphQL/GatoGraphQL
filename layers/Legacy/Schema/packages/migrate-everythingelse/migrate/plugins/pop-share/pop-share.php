<?php
/*
Plugin Name: PoP Share
Description: Implementation of Share for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SHARE_VERSION', 0.132);
define('POP_SHARE_DIR', dirname(__FILE__));

class PoP_Share
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        define('POP_SHARE_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SHARE_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Share_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Share_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Share();
