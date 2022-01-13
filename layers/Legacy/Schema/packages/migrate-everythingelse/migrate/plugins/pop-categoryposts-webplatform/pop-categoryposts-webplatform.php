<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Category Posts Web Platform
Description: Implementation of Content Category Posts Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CATEGORYPOSTSWEBPLATFORM_VERSION', 0.132);
define('POP_CATEGORYPOSTSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_CATEGORYPOSTSWEBPLATFORM_PHPTEMPLATES_DIR', POP_CATEGORYPOSTSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_CategoryPostsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Blog Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888520);
    }
    public function init()
    {
        define('POP_CATEGORYPOSTSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CATEGORYPOSTSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CategoryPostsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CategoryPostsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CategoryPostsWebPlatform();
