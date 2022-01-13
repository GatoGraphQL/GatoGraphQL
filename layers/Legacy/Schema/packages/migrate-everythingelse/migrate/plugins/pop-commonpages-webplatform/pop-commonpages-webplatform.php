<?php
/*
Plugin Name: PoP Common Pages Web Platform
Version: 0.1
Description: Processors for different Sections for the Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONPAGESWEBPLATFORM_VERSION', 0.108);
define('POP_COMMONPAGESWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_CommonPagesWebPlatform
{
    public function __construct()
    {

        // Priority: after ...
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888890);
    }

    public function init()
    {
        define('POP_COMMONPAGESWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONPAGESWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonPagesWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonPagesWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CommonPagesWebPlatform();
