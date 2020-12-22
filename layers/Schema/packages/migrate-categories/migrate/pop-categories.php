<?php
/*
Plugin Name: PoP Categories
Version: 0.1
Description: The foundation for a PoP Categories
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\Categories;
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CATEGORIES_VERSION', 0.106);
define('POP_CATEGORIES_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {
        // Priority: new section, after PoP Posts
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 205);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CATEGORIES_INITIALIZED', true);
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
