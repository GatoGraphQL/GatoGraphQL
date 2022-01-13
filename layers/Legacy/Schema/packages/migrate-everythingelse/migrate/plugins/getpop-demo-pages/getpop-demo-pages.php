<?php
/*
Plugin Name: GetPoP Demo Pages
Version: 0.1
Description: Processors for the GetPoP Demo website, implemented using the Wassup Theme for the PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('GETPOPDEMO_PAGES_VERSION', 0.107);
define('GETPOPDEMO_PAGES_DIR', dirname(__FILE__));

class GetPoPDemo_Pages
{
    public function __construct()
    {

        // Priority: after PoP Application Category Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888820);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('GETPOPDEMO_PAGES_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new GetPoPDemo_Pages_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new GetPoPDemo_Pages_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new GetPoPDemo_Pages();
