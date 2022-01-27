<?php
/*
Plugin Name: qTranslate-X for PoP
Version: 0.1
Description: Integration with plug-in qTranslate-X for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('QTX_POP_VERSION', 0.109);
define('QTX_POP_DIR', dirname(__FILE__));

class QTX_PoP
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\Root\App::addFilter(
            'PoP_Multilingual_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP WordPress Application
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function getProviderValidationClass($class)
    {
        return QTX_PoP_Validation::class;
    }

    public function init()
    {
        define('QTX_POP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('QTX_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('QTX_Translator');
        return true;
        // require_once 'validation.php';
        $validation = new QTX_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new QTX_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new QTX_PoP();
