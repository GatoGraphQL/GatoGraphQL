<?php
/*
Plugin Name: PoP Newsletter
Description: Implementation of Newsletter for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NEWSLETTER_VERSION', 0.132);
define('POP_NEWSLETTER_DIR', dirname(__FILE__));

class PoP_Newsletter
{
    public function __construct()
    {

        // Priority: after PoP User Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888340);
    }
    public function init()
    {
        define('POP_NEWSLETTER_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_NEWSLETTER_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Newsletter_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Newsletter_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Newsletter();
