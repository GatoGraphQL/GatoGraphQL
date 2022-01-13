<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Share Processors
Description: Implementation of Share Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SHAREPROCESSORS_VERSION', 0.132);
define('POP_SHAREPROCESSORS_DIR', dirname(__FILE__));
define('POP_SHAREPROCESSORS_PHPTEMPLATES_DIR', POP_SHAREPROCESSORS_DIR.'/php-templates/compiled');

class PoP_ShareProcessors
{
    public function __construct()
    {

        // Priority: after PoP Forms Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888820);
    }
    public function init()
    {
        define('POP_SHAREPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SHAREPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ShareProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ShareProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ShareProcessors();
