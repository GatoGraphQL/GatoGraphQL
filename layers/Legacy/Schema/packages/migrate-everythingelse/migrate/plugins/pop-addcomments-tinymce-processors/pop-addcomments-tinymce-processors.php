<?php
/*
Plugin Name: PoP Add Comments with TinyMCE Processors
Description: Implementation of Add Comments with TinyMCE Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOMMENTSTINYMCEPROCESSORS_VERSION', 0.132);
define('POP_ADDCOMMENTSTINYMCEPROCESSORS_DIR', dirname(__FILE__));
define('POP_ADDCOMMENTSTINYMCEPROCESSORS_PHPTEMPLATES_DIR', POP_ADDCOMMENTSTINYMCEPROCESSORS_DIR.'/php-templates/compiled');

class PoP_AddCommentsTinyMCEProcessors
{
    public function __construct()
    {

        // Priority: after PoP Content Creation Processors and PoP Add Comments Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888855);
    }
    public function init()
    {
        define('POP_ADDCOMMENTSTINYMCEPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOMMENTSTINYMCEPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddCommentsTinyMCEProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddCommentsTinyMCEProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddCommentsTinyMCEProcessors();
