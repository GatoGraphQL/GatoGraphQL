<?php
/*
Plugin Name: PoP Meta
Version: 0.1
Description: The foundation for a PoP Meta
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\Meta;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_META_VERSION', 0.106);
define('POP_META_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: new section, after PoP CMS Model
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888204);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_META_INITIALIZED', true);
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
