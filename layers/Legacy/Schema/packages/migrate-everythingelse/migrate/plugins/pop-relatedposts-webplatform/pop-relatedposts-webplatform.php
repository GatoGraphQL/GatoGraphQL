<?php
/*
Plugin Name: PoP Related Posts Web Platform
Description: Implementation of Related Posts Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_RELATEDPOSTSWEBPLATFORM_VERSION', 0.132);
define('POP_RELATEDPOSTSWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_RelatedPostsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Add Comments TinyMCE Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888560);
    }
    public function init()
    {
        define('POP_RELATEDPOSTSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_RELATEDPOSTSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_RelatedPostsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_RelatedPostsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_RelatedPostsWebPlatform();
