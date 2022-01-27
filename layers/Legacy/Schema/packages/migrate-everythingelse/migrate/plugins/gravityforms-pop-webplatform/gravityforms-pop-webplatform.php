<?php
/*
Plugin Name: Gravity Forms for PoP Web Platform
Version: 0.1
Description: Integration of plugin Gravity Forms with PoP.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('GFPOPWEBPLATFORM_VERSION', 0.176);

define('GFPOPWEBPLATFORM_DIR', dirname(__FILE__));

class GFPoPWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('GFPOPWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('GFPOPWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('GFForms');
        return true;
        include_once 'validation.php';
        $validation = new GFPoPWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new GFPoPWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new GFPoPWebPlatform();
