<?php
/*
Plugin Name: PoP Content Post Links Creation
Version: 0.1
Description: The foundation for a PoP Content Post Links Creation
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTPOSTLINKSCREATION_VERSION', 0.106);
define('POP_CONTENTPOSTLINKSCREATION_DIR', dirname(__FILE__));

class PoP_ContentPostLinksCreation
{
    public function __construct()
    {

        // Priority: after PoP Posts Creation
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTPOSTLINKSCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentPostLinksCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentPostLinksCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinksCreation();
