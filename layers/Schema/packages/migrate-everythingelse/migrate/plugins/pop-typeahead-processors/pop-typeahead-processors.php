<?php
/*
Plugin Name: PoP Typeahead Processors
Version: 0.1
Description: Implementation of processors for the Typeahead for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TYPEAHEADPROCESSORS_VERSION', 0.111);
define('POP_TYPEAHEADPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('POP_TYPEAHEADPROCESSORS_DIR', dirname(__FILE__));

class PoP_TypeaheadProcessors
{
    public function __construct()
    {
        
        // Priority: after PoP Application Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 810);
    }
    public function init()
    {
        define('POP_TYPEAHEADPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_TYPEAHEADPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_TypeaheadProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_TypeaheadProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_TypeaheadProcessors();
