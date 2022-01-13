<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Related Posts Processors
Description: Implementation of Related Posts Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_RELATEDPOSTSPROCESSORS_VERSION', 0.132);
define('POP_RELATEDPOSTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_RelatedPostsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888850);
    }
    public function init()
    {
        define('POP_RELATEDPOSTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_RELATEDPOSTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_RelatedPostsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_RelatedPostsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_RelatedPostsProcessors();
