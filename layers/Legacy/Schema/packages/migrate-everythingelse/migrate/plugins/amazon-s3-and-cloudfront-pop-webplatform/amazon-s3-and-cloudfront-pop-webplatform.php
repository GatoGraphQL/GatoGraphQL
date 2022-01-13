<?php
/*
Plugin Name: WP Offload S3 Lite for PoP Web Platform
Version: 0.1
Description: Integration of plugin WP Offload S3 Lite with PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('AWSS3CFPOPWEBPLATFORM_VERSION', 0.176);

define('AWSS3CFPOPWEBPLATFORM_DIR', dirname(__FILE__));

class AWSS3CFPoPWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('AWSS3CFPOPWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('AWSS3CFPOPWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('as3cf_init');
        return true;
        include_once 'validation.php';
        $validation = new AWSS3CFPoPWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new AWSS3CFPoPWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new AWSS3CFPoPWebPlatform();
