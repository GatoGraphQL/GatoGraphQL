<?php
/*
Plugin Name: PoP Content Post Links Processors
Description: Implementation of Content Post Links Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTPOSTLINKSPROCESSORS_VERSION', 0.132);
define('POP_CONTENTPOSTLINKSPROCESSORS_DIR', dirname(__FILE__));

class PoP_ContentPostLinksProcessors
{
    public function __construct()
    {

        // Priority: after PoP Category Posts Processors
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888830);
    }
    public function init()
    {
        define('POP_CONTENTPOSTLINKSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTPOSTLINKSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentPostLinksProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentPostLinksProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinksProcessors();
