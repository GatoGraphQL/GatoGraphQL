<?php
/*
Plugin Name: PoP Multilingual Web Platform
Version: 0.1
Description: Implementation of PoP Multilingual Web Platform for PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('POP_MULTILINGUALWEBPLATFORM_VERSION', 0.109);
define('POP_MULTILINGUALWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_MultilingualWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('POP_MULTILINGUALWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_MULTILINGUALWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('QTX_Translator');
        return true;
        include_once 'validation.php';
        $validation = new PoP_MultilingualWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MultilingualWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MultilingualWebPlatform();
