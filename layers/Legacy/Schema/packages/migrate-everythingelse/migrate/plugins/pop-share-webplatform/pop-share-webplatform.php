<?php
/*
Plugin Name: PoP Share Web Platform
Description: Implementation of Share Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SHAREWEBPLATFORM_VERSION', 0.132);
define('POP_SHAREWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_SHAREWEBPLATFORM_PHPTEMPLATES_DIR', POP_SHAREWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_ShareWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888510);
    }
    public function init()
    {
        define('POP_SHAREWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SHAREWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ShareWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ShareWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ShareWebPlatform();
