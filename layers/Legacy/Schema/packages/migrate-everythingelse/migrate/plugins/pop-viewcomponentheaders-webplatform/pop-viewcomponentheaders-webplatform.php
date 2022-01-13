<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Viewcomponent Headers Web Platform
Description: Implementation of Viewcomponent Headers Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_VIEWCOMPONENTHEADERSWEBPLATFORM_VERSION', 0.132);
define('POP_VIEWCOMPONENTHEADERSWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_VIEWCOMPONENTHEADERSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_VIEWCOMPONENTHEADERSWEBPLATFORM_PHPTEMPLATES_DIR', POP_VIEWCOMPONENTHEADERSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_ViewcomponentHeadersWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }
    public function init()
    {
        define('POP_VIEWCOMPONENTHEADERSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_VIEWCOMPONENTHEADERSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ViewcomponentHeadersWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ViewcomponentHeadersWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ViewcomponentHeadersWebPlatform();
