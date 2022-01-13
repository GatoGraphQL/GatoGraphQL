<?php
/*
Plugin Name: PoP CSS Converter Web Platform
Description: Implementation of EventLinks Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CSSCONVERTERWEBPLATFORM_VERSION', 0.132);
define('POP_CSSCONVERTERWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_CSSConverterWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888510);
    }
    public function init()
    {
        define('POP_CSSCONVERTERWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CSSCONVERTERWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CSSConverterWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CSSConverterWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CSSConverterWebPlatform();
