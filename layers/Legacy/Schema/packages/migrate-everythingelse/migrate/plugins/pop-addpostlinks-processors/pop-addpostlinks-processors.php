<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Post Links Processors
Description: Implementation of Add Post Links Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDPOSTLINKSPROCESSORS_VERSION', 0.132);
define('POP_ADDPOSTLINKSPROCESSORS_DIR', dirname(__FILE__));
define('POP_ADDPOSTLINKSPROCESSORS_PHPTEMPLATES_DIR', POP_ADDPOSTLINKSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_AddPostLinksProcessors
{
    public function __construct()
    {

        // Priority: after PoP Content Creation Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888860);
    }
    public function init()
    {
        define('POP_ADDPOSTLINKSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDPOSTLINKSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddPostLinksProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddPostLinksProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddPostLinksProcessors();
