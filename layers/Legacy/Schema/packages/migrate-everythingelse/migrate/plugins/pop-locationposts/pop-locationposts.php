<?php
/*
Plugin Name: PoP Location Posts
Version: 0.1
Description: Implementation of PoP Location Posts
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTS_VERSION', 0.106);
define('POP_LOCATIONPOSTS_DIR', dirname(__FILE__));

class PoP_LocationPosts
{
    public function __construct()
    {

        // Priority: after PoP Locations
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPosts_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPosts_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPosts();
