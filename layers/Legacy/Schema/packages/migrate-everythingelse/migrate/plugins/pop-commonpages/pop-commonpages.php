<?php
/*
Plugin Name: PoP Common Pages
Version: 0.1
Description: Processors for different Sections for the Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONPAGES_VERSION', 0.108);
define('POP_COMMONPAGES_DIR', dirname(__FILE__));

class PoP_CommonPages
{
    public function __construct()
    {

        // Priority: after ...
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888880);
    }

    public function init()
    {
        define('POP_COMMONPAGES_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONPAGES_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonPages_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonPages_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CommonPages();
