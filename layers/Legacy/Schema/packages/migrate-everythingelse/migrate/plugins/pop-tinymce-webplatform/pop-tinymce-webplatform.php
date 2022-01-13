<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP TinyMCE Web Platform
Description: Implementation of TinyMCE Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TINYMCEWEBPLATFORM_VERSION', 0.132);
define('POP_TINYMCEWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_TINYMCEWEBPLATFORM_PHPTEMPLATES_DIR', POP_TINYMCEWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_TinyMCEWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }
    public function init()
    {
        define('POP_TINYMCEWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_TINYMCEWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_TinyMCEWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_TinyMCEWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_TinyMCEWebPlatform();
