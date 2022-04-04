<?php
/*
Plugin Name: PoP Single-Page Application Web Platform
Description: Implementation of SPA capabilities for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SPAWEBPLATFORM_VERSION', 0.157);
define('POP_SPAWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_SPAWEBPLATFORM_PHPTEMPLATES_DIR', POP_SPAWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_SPAWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Server-Side Rendering, inner circle
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888404);
    }
    public function init()
    {
        define('POP_SPAWEBPLATFORM_URL', plugins_url('', __FILE__));
        if ($this->validate()) {
            $this->initialize();
            define('POP_SPAWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SPAWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SPAWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SPAWebPlatform();
