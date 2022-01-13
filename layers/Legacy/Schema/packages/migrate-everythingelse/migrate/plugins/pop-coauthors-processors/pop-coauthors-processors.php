<?php
/*
Plugin Name: PoP Coauthors Processors
Version: 0.1
Description: The foundation for a PoP Coauthors Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COAUTHORSPROCESSORS_VERSION', 0.106);
define('POP_COAUTHORSPROCESSORS_DIR', dirname(__FILE__));

class PoP_CoauthorsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888810);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COAUTHORSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('coauthors_plus');
        return true;
        include_once 'validation.php';
        $validation = new PoP_CoauthorsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CoauthorsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CoauthorsProcessors();
