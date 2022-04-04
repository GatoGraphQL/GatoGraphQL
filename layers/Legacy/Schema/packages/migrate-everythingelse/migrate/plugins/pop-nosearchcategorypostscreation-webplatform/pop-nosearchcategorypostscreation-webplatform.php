<?php
/*
Plugin Name: PoP No Search Category Posts Creation Web Platform
Description: Implementation of EventLinks Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NOSEARCHCATEGORYPOSTSCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_NOSEARCHCATEGORYPOSTSCREATIONWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_NoSearchCategoryPostsCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888510);
    }
    public function init()
    {
        define('POP_NOSEARCHCATEGORYPOSTSCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_NOSEARCHCATEGORYPOSTSCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NoSearchCategoryPostsCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NoSearchCategoryPostsCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NoSearchCategoryPostsCreationWebPlatform();
