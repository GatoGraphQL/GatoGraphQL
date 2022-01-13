<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Category Posts Processors
Description: Implementation of Content Posts Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CATEGORYPOSTSPROCESSORS_VERSION', 0.132);
define('POP_CATEGORYPOSTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_CategoryPostsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Blog Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888820);
    }
    public function init()
    {
        define('POP_CATEGORYPOSTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CATEGORYPOSTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CategoryPostsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CategoryPostsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CategoryPostsProcessors();
