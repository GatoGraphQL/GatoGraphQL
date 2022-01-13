<?php
/*
Plugin Name: PoP Add Related Posts Processors
Description: Implementation of Add Related Posts Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDRELATEDPOSTSPROCESSORS_VERSION', 0.132);
define('POP_ADDRELATEDPOSTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_AddRelatedPostsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Related Posts Processors and PoP Content Creation Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888860);
    }
    public function init()
    {
        define('POP_ADDRELATEDPOSTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDRELATEDPOSTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddRelatedPostsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddRelatedPostsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddRelatedPostsProcessors();
