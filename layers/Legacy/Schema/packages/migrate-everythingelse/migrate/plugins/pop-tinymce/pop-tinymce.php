<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP TinyMCE
Description: Implementation of TinyMCE for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TINYMCE_VERSION', 0.132);
define('POP_TINYMCE_DIR', dirname(__FILE__));

class PoP_TinyMCE
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        define('POP_TINYMCE_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_TINYMCE_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_TinyMCE_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_TinyMCE_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_TinyMCE();
