<?php
/*
Plugin Name: PoP Content Creation Processors
Description: Implementation of Content Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTCREATIONPROCESSORS_VERSION', 0.132);
define('POP_CONTENTCREATIONPROCESSORS_DIR', dirname(__FILE__));
define('POP_CONTENTCREATIONPROCESSORS_PHPTEMPLATES_DIR', POP_CONTENTCREATIONPROCESSORS_DIR.'/php-templates/compiled');

class PoP_ContentCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888850);
    }
    public function init()
    {
        define('POP_CONTENTCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentCreationProcessors();
