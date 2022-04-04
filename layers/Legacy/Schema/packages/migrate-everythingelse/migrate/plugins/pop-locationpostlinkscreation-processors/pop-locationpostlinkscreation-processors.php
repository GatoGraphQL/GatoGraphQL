<?php
/*
Plugin Name: PoP Location Post Links Creation Processors
Description: Implementation of Location Post Links Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTLINKSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_LOCATIONPOSTLINKSCREATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_LocationPostLinksCreationProcessors
{
    public function __construct()
    {

        // // Priority: after PoP Location Posts Creation Processors
        // \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888870);
        // Priority: after PoP Location Posts Creation Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888910);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTLINKSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTLINKSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostLinksCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostLinksCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinksCreationProcessors();
