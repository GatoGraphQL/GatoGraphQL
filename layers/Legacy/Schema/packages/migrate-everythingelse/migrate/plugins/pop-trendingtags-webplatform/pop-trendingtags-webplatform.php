<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Trending Tags Web Platform
Description: Implementation of Trending Tags Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TRENDINGTAGSWEBPLATFORM_VERSION', 0.132);
define('POP_TRENDINGTAGSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_TRENDINGTAGSWEBPLATFORM_PHPTEMPLATES_DIR', POP_TRENDINGTAGSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_TrendingTagsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Locations Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888550);
    }
    public function init()
    {
        define('POP_TRENDINGTAGSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_TRENDINGTAGSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_TrendingTagsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_TrendingTagsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_TrendingTagsWebPlatform();
