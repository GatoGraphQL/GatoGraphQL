<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Add Post Links Web Platform
Description: Implementation of Add Post Links Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDPOSTLINKSWEBPLATFORM_VERSION', 0.132);
define('POP_ADDPOSTLINKSWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_ADDPOSTLINKSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_ADDPOSTLINKSWEBPLATFORM_PHPTEMPLATES_DIR', POP_ADDPOSTLINKSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_AddPostLinksWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Notifications Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }
    public function init()
    {
        define('POP_ADDPOSTLINKSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDPOSTLINKSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddPostLinksWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddPostLinksWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddPostLinksWebPlatform();
