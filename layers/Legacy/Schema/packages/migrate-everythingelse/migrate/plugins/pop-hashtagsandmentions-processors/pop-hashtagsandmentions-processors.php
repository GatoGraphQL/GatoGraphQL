<?php
/*
Plugin Name: PoP Hashtags and Mentions Processors
Version: 0.1
Description: Implementation of processors for the Hashtags and Mentions for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_HASHTAGSANDMENTIONSPROCESSORS_VERSION', 0.111);
define('POP_HASHTAGSANDMENTIONSPROCESSORS_VENDORRESOURCESVERSION', 0.100);
define('POP_HASHTAGSANDMENTIONSPROCESSORS_DIR', dirname(__FILE__));

class PoP_HashtagsAndMentionsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888810);
    }
    public function init()
    {
        define('POP_HASHTAGSANDMENTIONSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_HASHTAGSANDMENTIONSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_HashtagsAndMentionsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_HashtagsAndMentionsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_HashtagsAndMentionsProcessors();
