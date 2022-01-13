<?php
/*
Plugin Name: PoP PrettyPrint
Version: 0.1
Description: Functionality for doing the PrettyPrint using Google's library: https://github.com/google/code-prettify
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_PRETTYPRINT_VERSION', 0.109);
define('POP_PRETTYPRINT_VENDORRESOURCESVERSION', 0.100);
define('POP_PRETTYPRINT_DIR', dirname(__FILE__));

class PoP_PrettyPrint
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('POP_PRETTYPRINT_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_PRETTYPRINT_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_PrettyPrint_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PrettyPrint_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PrettyPrint();
