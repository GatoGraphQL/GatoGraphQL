<?php
/*
Plugin Name: PoP Blog
Version: 0.1
Description: The foundation for a PoP Blog
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BLOG_VERSION', 0.106);
define('POP_BLOG_DIR', dirname(__FILE__));

class PoP_Blog
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_BLOG_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Blog_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Blog_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Blog();
