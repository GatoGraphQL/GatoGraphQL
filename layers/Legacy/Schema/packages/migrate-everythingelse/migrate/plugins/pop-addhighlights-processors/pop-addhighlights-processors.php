<?php
/*
Plugin Name: PoP Add Highlights Processors
Description: Implementation of Add Highlights Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDHIGHLIGHTSPROCESSORS_VERSION', 0.132);
define('POP_ADDHIGHLIGHTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_AddHighlightsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Related Posts Processors and PoP Content Creation Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888860);
    }
    public function init()
    {
        define('POP_ADDHIGHLIGHTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDHIGHLIGHTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddHighlightsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddHighlightsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddHighlightsProcessors();
