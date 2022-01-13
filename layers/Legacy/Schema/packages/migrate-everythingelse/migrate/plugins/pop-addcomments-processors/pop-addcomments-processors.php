<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Comments Processors
Description: Implementation of Add Comments Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOMMENTSPROCESSORS_VERSION', 0.132);
define('POP_ADDCOMMENTSPROCESSORS_DIR', dirname(__FILE__));
define('POP_ADDCOMMENTSPROCESSORS_PHPTEMPLATES_DIR', POP_ADDCOMMENTSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_AddCommentsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888850);
    }
    public function init()
    {
        define('POP_ADDCOMMENTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOMMENTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddCommentsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddCommentsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddCommentsProcessors();
