<?php
/*
Plugin Name: PoP CSS Converter
Version: 0.1
Description: Library for converting CSS into its corresponding styles, used for sending emails through PoP processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CSSCONVERTER_VERSION', 0.106);
define('POP_CSSCONVERTER_DIR', dirname(__FILE__));
define('POP_CSSCONVERTER_VENDOR_DIR', POP_CSSCONVERTER_DIR.'/vendor/sabberworm/php-css-parser');

class PoP_CSSConverter
{
    public function __construct()
    {

        // Priority: after PoP System
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888130);
    }
    public function init()
    {
        define('POP_CSSCONVERTER_BUILD_DIR', POP_BUILD_DIR.'/pop-cssconverter');

        if ($this->validate()) {
            $this->initialize();
            define('POP_CSSCONVERTER_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CSSConverter_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CSSConverter_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CSSConverter();
