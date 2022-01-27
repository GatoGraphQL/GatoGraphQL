<?php
/*
Plugin Name: PoP AWS
Version: 0.1
Description: Use AWS for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_AWS_VERSION', 0.107);
define('POP_AWS_DIR', dirname(__FILE__));

class PoP_AWS
{
    public function __construct()
    {

        // Priority: after PoP Engine, and before everything else (except the "website-environment" plug-ins),
        // so we can set the POP_CDNFOUNDATION_CDN_ASSETS_URI constant in plugin_url before all other plug-ins need it
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888110);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_AWS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AWS_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AWS_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AWS();
