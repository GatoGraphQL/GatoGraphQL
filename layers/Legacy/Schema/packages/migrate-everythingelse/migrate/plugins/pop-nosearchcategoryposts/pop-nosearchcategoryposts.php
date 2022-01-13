<?php
/*
Plugin Name: PoP No Search Category Posts
Version: 0.1
Description: Implementation of PoP No Search Category Posts
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NOSEARCHCATEGORYPOSTS_VERSION', 0.106);
define('POP_NOSEARCHCATEGORYPOSTS_DIR', dirname(__FILE__));

class PoP_NoSearchCategoryPosts
{
    public function __construct()
    {

        // Priority: after PoP Blog
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888320);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_NOSEARCHCATEGORYPOSTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NoSearchCategoryPosts_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NoSearchCategoryPosts_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NoSearchCategoryPosts();
