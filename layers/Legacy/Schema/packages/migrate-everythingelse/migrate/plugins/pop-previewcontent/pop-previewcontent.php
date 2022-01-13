<?php
/*
Plugin Name: PoP Preview Content
Version: 0.1
Description: The foundation for a PoP Preview Content
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_PREVIEWCONTENT_VERSION', 0.106);
define('POP_PREVIEWCONTENT_DIR', dirname(__FILE__));

class PoP_PreviewContent
{
    public function __construct()
    {

        // Priority: after PoP Content Creation
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_PREVIEWCONTENT_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('DS_Public_Post_Preview');
        return true;
        include_once 'validation.php';
        $validation = new PoP_PreviewContent_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PreviewContent_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PreviewContent();
