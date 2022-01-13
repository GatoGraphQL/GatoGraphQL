<?php
/*
Plugin Name: PoP Location Post Links
Version: 0.1
Description: Implementation of PoP Location Post Links
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTLINKS_VERSION', 0.106);
define('POP_LOCATIONPOSTLINKS_DIR', dirname(__FILE__));

class PoP_LocationPostLinks
{
    public function __construct()
    {

        // Priority: after PoP Location Posts
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTLINKS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostLinks_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostLinks_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinks();
