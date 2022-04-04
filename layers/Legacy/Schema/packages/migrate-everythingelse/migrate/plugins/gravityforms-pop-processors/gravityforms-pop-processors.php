<?php
/*
Plugin Name: Gravity Forms for PoP Processors
Version: 0.1
Description: Integration of plugin Gravity Forms with PoP.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('GFPOPPROCESSORS_VERSION', 0.176);

define('GFPOPPROCESSORS_DIR', dirname(__FILE__));

class GFPoPProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888810);
    }

    public function init()
    {
        define('GFPOPPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('GFPOPPROCESSORS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('GFForms');
        return true;
        include_once 'validation.php';
        $validation = new GFPoPProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new GFPoPProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new GFPoPProcessors();
