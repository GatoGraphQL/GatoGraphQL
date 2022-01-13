<?php
/*
Plugin Name: PoP Multilingual
Version: 0.1
Description: The foundation for a PoP Multilingual
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MULTILINGUAL_VERSION', 0.106);
define('POP_MULTILINGUAL_DIR', dirname(__FILE__));

class PoP_Multilingual
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_MULTILINGUAL_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('QTX_Translator');
        return true;
        include_once 'validation.php';
        $validation = new PoP_Multilingual_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Multilingual_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Multilingual();
