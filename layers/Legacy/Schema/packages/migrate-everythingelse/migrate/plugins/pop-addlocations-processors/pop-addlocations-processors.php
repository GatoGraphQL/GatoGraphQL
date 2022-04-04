<?php
/*
Plugin Name: PoP Add Locations Processors
Description: Implementation of Add Locations Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDLOCATIONSPROCESSORS_VERSION', 0.132);
define('POP_ADDLOCATIONSPROCESSORS_DIR', dirname(__FILE__));
define('POP_ADDLOCATIONSPROCESSORS_PHPTEMPLATES_DIR', POP_ADDLOCATIONSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_AddLocationsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Locations Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888880);
    }
    public function init()
    {
        define('POP_ADDLOCATIONSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDLOCATIONSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddLocationsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddLocationsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddLocationsProcessors();
