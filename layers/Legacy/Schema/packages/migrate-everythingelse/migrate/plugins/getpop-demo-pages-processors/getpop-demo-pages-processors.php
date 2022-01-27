<?php
/*
Plugin Name: GetPoP Demo Pages Processors
Version: 0.1
Description: Processors for the GetPoP Demo website, implemented using the Wassup Theme for the PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('GETPOPDEMO_PAGESPROCESSORS_VERSION', 0.107);
define('GETPOPDEMO_PAGESPROCESSORS_DIR', dirname(__FILE__));

class GetPoPDemo_PagesProcessors
{
    public function __construct()
    {

        // Priority: after PoP GetPoP Demo Pages
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888830);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('GETPOPDEMO_PAGESPROCESSORS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new GetPoPDemo_PagesProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new GetPoPDemo_PagesProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new GetPoPDemo_PagesProcessors();
