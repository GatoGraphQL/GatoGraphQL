<?php
/*
Plugin Name: Gravity Forms for PoP Generic Forms
Version: 0.1
Description: Implementation of the Generic Forms plugin using Gravity Forms for PoP sites.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_GFPOPGENERICFORMS_VERSION', 0.176);

define('POP_GFPOPGENERICFORMS_DIR', dirname(__FILE__));

class GFPoPGenericForms
{
    public function __construct()
    {

        // Priority: after PoP Social Network Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888870);
    }

    public function init()
    {
        define('POP_GFPOPGENERICFORMS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_GFPOPGENERICFORMS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('GFForms');
        return true;
        include_once 'validation.php';
        $validation = new GFPoPGenericForms_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new GFPoPGenericForms_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new GFPoPGenericForms();
