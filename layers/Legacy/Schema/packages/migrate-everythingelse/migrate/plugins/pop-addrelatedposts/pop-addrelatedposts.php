<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Related Posts
Description: Implementation of Add Related Posts for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDRELATEDPOSTS_VERSION', 0.132);
define('POP_ADDRELATEDPOSTS_DIR', dirname(__FILE__));

class PoP_AddRelatedPosts
{
    public function __construct()
    {

        // Priority: after PoP Related Posts and PoP Content Creation
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888360);
    }
    public function init()
    {
        define('POP_ADDRELATEDPOSTS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDRELATEDPOSTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddRelatedPosts_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddRelatedPosts_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddRelatedPosts();
