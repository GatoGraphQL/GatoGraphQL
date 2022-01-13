<?php
/*
Plugin Name: PoP Forms
Version: 0.1
Description: Implementation of forms for PoP sites.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_FORMS_VERSION', 0.176);
define('POP_FORMS_DIR', dirname(__FILE__));

class PoP_Forms
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888305);
    }

    public function init()
    {
        define('POP_FORMS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_FORMS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Forms_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Forms_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Forms();
