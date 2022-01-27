<?php
/*
Plugin Name: PoP Application Web Platform
Description: Implementation of Application Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_APPLICATIONWEBPLATFORM_VERSION', 0.132);
define('POP_APPLICATIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_APPLICATIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_APPLICATIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_ApplicationWebPlatform
{
    public function __construct()
    {

        // Priority: new section, after PoP Engine Web Platform section
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888500);
    }
    public function init()
    {
        define('POP_APPLICATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_APPLICATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ApplicationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ApplicationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ApplicationWebPlatform();
