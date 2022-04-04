<?php
/*
Plugin Name: PoP Viewcomponent Headers Processors
Version: 0.1
Description: Implementation of processors for the Viewcomponent Headers for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_VIEWCOMPONENTHEADERSPROCESSORS_VERSION', 0.111);
define('POP_VIEWCOMPONENTHEADERSPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('POP_VIEWCOMPONENTHEADERSPROCESSORS_DIR', dirname(__FILE__));

class PoP_ViewcomponentHeadersProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888810);
    }
    public function init()
    {
        define('POP_VIEWCOMPONENTHEADERSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_VIEWCOMPONENTHEADERSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ViewcomponentHeadersProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ViewcomponentHeadersProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ViewcomponentHeadersProcessors();
