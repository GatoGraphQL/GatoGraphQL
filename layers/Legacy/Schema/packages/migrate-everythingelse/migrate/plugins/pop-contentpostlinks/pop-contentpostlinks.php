<?php
/*
Plugin Name: PoP Content Post Links
Version: 0.1
Description: Implementation of PoP Content Post Links
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTPOSTLINKS_VERSION', 0.106);
define('POP_CONTENTPOSTLINKS_DIR', dirname(__FILE__));

class PoP_ContentPostLinks
{
    public function __construct()
    {

        // Priority: after PoP Blog
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888320);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTPOSTLINKS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentPostLinks_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentPostLinks_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentPostLinks();
