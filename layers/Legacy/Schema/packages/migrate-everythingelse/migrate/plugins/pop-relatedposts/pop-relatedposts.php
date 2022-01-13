<?php
/*
Plugin Name: PoP Related Posts
Version: 0.1
Description: The foundation for a PoP Related Posts
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_RELATEDPOSTS_VERSION', 0.106);
define('POP_RELATEDPOSTS_DIR', dirname(__FILE__));

class PoP_RelatedPosts
{
    public function __construct()
    {

        // Priority: after PoP Notifications, and before PoP Posts Creation and others
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888349);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_RELATEDPOSTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_RelatedPosts_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_RelatedPosts_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_RelatedPosts();
