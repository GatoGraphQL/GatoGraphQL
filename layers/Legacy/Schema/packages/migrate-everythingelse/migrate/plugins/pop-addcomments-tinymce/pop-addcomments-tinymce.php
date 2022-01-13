<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Comments with TinyMCE
Description: Implementation of Add Comments with TinyMCE for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOMMENTSTINYMCE_VERSION', 0.132);
define('POP_ADDCOMMENTSTINYMCE_DIR', dirname(__FILE__));

class PoP_AddCommentsTinyMCE
{
    public function __construct()
    {

        // Priority: after PoP Content Creation and PoP Add Comments
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888355);
    }
    public function init()
    {
        define('POP_ADDCOMMENTSTINYMCE_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOMMENTSTINYMCE_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddCommentsTinyMCE_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddCommentsTinyMCE_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddCommentsTinyMCE();
