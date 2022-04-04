<?php
/*
Plugin Name: PoP WordPress CMS
Version: 0.1
Description: Implementation of WordPress functions for PoP EmailSender
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\EmailSender\WP;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EMAILSENDERWP_VERSION', 0.106);
define('POP_EMAILSENDERWP_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: after PoP Email Sender
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888320);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_EMAILSENDERWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugins();
