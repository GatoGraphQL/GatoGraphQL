<?php
/*
Plugin Name: PoP Add Coauthors Processors
Description: Implementation of Add Coauthors Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOAUTHORSPROCESSORS_VERSION', 0.132);
define('POP_ADDCOAUTHORSPROCESSORS_DIR', dirname(__FILE__));
define('POP_ADDCOAUTHORSPROCESSORS_PHPTEMPLATES_DIR', POP_ADDCOAUTHORSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_AddCoauthorsProcessors
{
    public function __construct()
    {

        // Priority: after PoP User Platform Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888840);
    }
    public function init()
    {
        define('POP_ADDCOAUTHORSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOAUTHORSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddCoauthorsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddCoauthorsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddCoauthorsProcessors();
