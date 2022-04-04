<?php
/*
Plugin Name: PoP Forms Processors
Description: Implementation of Forms Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_FORMSPROCESSORS_VERSION', 0.132);
define('POP_FORMSPROCESSORS_DIR', dirname(__FILE__));
define('POP_FORMSPROCESSORS_PHPTEMPLATES_DIR', POP_FORMSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_FormsProcessors
{
    public function __construct()
    {

        // Priority: before PoP Application Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888790);
    }
    public function init()
    {
        define('POP_FORMSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_FORMSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_FormsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_FormsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_FormsProcessors();
