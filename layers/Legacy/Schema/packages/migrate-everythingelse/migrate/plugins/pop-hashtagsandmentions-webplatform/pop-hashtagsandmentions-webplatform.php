<?php
/*
Plugin Name: PoP Hashtags and Mentions Web Platform
Description: Implementation of Hashtags and Mentions Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_HASHTAGSANDMENTIONSWEBPLATFORM_VERSION', 0.132);
define('POP_HASHTAGSANDMENTIONSWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_HASHTAGSANDMENTIONSWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_HASHTAGSANDMENTIONSWEBPLATFORM_PHPTEMPLATES_DIR', POP_HASHTAGSANDMENTIONSWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_HashtagsAndMentionsWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888510);
    }
    public function init()
    {
        define('POP_HASHTAGSANDMENTIONSWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_HASHTAGSANDMENTIONSWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_HashtagsAndMentionsWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_HashtagsAndMentionsWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_HashtagsAndMentionsWebPlatform();
