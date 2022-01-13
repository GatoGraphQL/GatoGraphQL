<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Location Posts Web Platform
Description: Implementation of Location Posts Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTSWEBPLATFORM_VERSION', 0.132);
define('POP_LOCATIONPOSTSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_LOCATIONPOSTSWEBPLATFORM_PHPTEMPLATES_DIR', POP_LOCATIONPOSTSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_LocationPostsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Locations Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsWebPlatform();
