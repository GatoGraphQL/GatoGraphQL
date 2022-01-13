<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Content Post Links Creation Processors
Description: Implementation of Content Post Links Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_ContentPostLinksCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Posts Creation Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888860);
    }
    public function init()
    {
        define('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTPOSTLINKSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentPostLinksCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentPostLinksCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinksCreationProcessors();
