<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Content Creation Web Platform
Description: Implementation of Content Creation Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONTENTCREATIONWEBPLATFORM_VERSION', 0.132);
define('POP_CONTENTCREATIONWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_CONTENTCREATIONWEBPLATFORM_PHPTEMPLATES_DIR', POP_CONTENTCREATIONWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_ContentCreationWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Locations Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }
    public function init()
    {
        define('POP_CONTENTCREATIONWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONTENTCREATIONWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ContentCreationWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ContentCreationWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ContentCreationWebPlatform();
