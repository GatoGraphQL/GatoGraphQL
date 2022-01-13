<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP No Search Category Posts Creation Processors
Description: Implementation of Content Category Posts Creation Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NOSEARCHCATEGORYPOSTSCREATIONPROCESSORS_VERSION', 0.132);
define('POP_NOSEARCHCATEGORYPOSTSCREATIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_NoSearchCategoryPostsCreationProcessors
{
    public function __construct()
    {

        // Priority: after PoP Posts Creation Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888860);
    }
    public function init()
    {
        define('POP_NOSEARCHCATEGORYPOSTSCREATIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_NOSEARCHCATEGORYPOSTSCREATIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NoSearchCategoryPostsCreationProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NoSearchCategoryPostsCreationProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NoSearchCategoryPostsCreationProcessors();
