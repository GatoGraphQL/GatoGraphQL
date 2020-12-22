<?php
use PoP\Hooks\Facades\HooksAPIFacade;
/*
Plugin Name: PoP Volunteering Processors
Description: Implementation of Volunteering Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_VOLUNTEERINGPROCESSORS_VERSION', 0.132);
define('POP_VOLUNTEERINGPROCESSORS_DIR', dirname(__FILE__));

class PoP_VolunteeringProcessors
{
    public function __construct()
    {

        // Priority: after PoP User Platform Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 840);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_VOLUNTEERINGPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_VolunteeringProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_VolunteeringProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_VolunteeringProcessors();
