<?php
/*
Plugin Name: PoP Trending Tags Processors
Description: Implementation of Trending Tags Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TRENDINGTAGSPROCESSORS_VERSION', 0.132);
define('POP_TRENDINGTAGSPROCESSORS_DIR', dirname(__FILE__));
define('POP_TRENDINGTAGSPROCESSORS_PHPTEMPLATES_DIR', POP_TRENDINGTAGSPROCESSORS_DIR.'/php-templates/compiled');

class PoP_TrendingTagsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Notifications Processors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888850);
    }
    public function init()
    {
        define('POP_TRENDINGTAGSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_TRENDINGTAGSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_TrendingTagsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_TrendingTagsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_TrendingTagsProcessors();
