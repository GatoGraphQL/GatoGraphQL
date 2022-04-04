<?php
/*
Plugin Name: PoP Locations Processors
Description: Implementation of Locations Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONSPROCESSORS_VERSION', 0.132);
define('POP_LOCATIONSPROCESSORS_DIR', dirname(__FILE__));
define('POP_LOCATIONSPROCESSORS_PHPTEMPLATES_DIR', POP_LOCATIONSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_LocationsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Social Network Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888870);
    }
    public function init()
    {
        define('POP_LOCATIONSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationsProcessors();
