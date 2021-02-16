<?php
/*
Plugin Name: PoP PostTags
Version: 0.1
Description: The foundation for a PoP PostTags
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\PostTags;
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_POSTTAGS_VERSION', 0.106);
define('POP_POSTTAGS_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {
        // Priority: new section, after PoP Posts
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888205);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_POSTTAGS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugins();
