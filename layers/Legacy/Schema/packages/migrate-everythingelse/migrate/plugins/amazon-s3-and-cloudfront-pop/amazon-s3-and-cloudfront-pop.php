<?php
/*
Plugin Name: WP Offload S3 Lite for PoP
Version: 0.1
Description: Integration of plugin WP Offload S3 Lite with PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('AWSS3CFPOP_VERSION', 0.176);

define('AWSS3CFPOP_DIR', dirname(__FILE__));

class AWSS3CFPoP
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }

    public function init()
    {
        define('AWSS3CFPOP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            // Execute after function as3cf_init in amazon-s3-and-cloudfront/wordpress-s3.php
            \PoP\Root\App::getHookManager()->addAction('aws_init', array($this, 'initialize'), 100);
            define('AWSS3CFPOP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('as3cf_init');
        return true;
        include_once 'validation.php';
        $validation = new AWSS3CFPoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new AWSS3CFPoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new AWSS3CFPoP();
