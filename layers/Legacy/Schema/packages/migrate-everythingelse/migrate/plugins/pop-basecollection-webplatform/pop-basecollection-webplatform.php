<?php
/*
Plugin Name: PoP Base Collection Web Platform
Version: 0.1
Description: Plug-in providing the base processors for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BASECOLLECTIONWEBPLATFORM_VERSION', 0.212);
define('POP_BASECOLLECTIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_BASECOLLECTIONWEBPLATFORM_VENDORRESOURCESVERSION', 0.200);
define('POP_BASECOLLECTIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_BASECOLLECTIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_BaseCollectionWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Server-Side Rendering
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888420);
    }
    public function init()
    {
        define('POP_BASECOLLECTIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_BASECOLLECTIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BaseCollectionWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BaseCollectionWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BaseCollectionWebPlatform();
