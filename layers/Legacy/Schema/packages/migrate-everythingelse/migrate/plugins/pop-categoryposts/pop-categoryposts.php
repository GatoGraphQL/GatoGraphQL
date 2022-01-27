<?php
/*
Plugin Name: PoP Category Posts
Version: 0.1
Description: Implementation of PoP Category Posts
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CATEGORYPOSTS_VERSION', 0.106);
define('POP_CATEGORYPOSTS_DIR', dirname(__FILE__));

class PoP_CategoryPosts
{
    public function __construct()
    {

        // Priority: after PoP Blog
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888320);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CATEGORYPOSTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CategoryPosts_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CategoryPosts_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CategoryPosts();
