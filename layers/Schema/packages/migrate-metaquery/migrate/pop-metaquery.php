<?php
/*
Plugin Name: PoP Meta Query
Version: 0.1
Description: The foundation for a PoP Meta Query
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoPSchema\MetaQuery;
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_METAQUERY_VERSION', 0.106);
define('POP_METAQUERY_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: new section, after PoP CMS Model and PoP Meta
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888210);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_METAQUERY_INITIALIZED', true);
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
