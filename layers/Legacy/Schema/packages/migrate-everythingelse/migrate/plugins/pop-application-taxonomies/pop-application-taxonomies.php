<?php
/*
Plugin Name: PoP ApplicationTaxonomies
Version: 0.1
Description: The foundation for a PoP ApplicationTaxonomies
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\ApplicationTaxonomies;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_APPLICATIONTAXONOMIES_VERSION', 0.106);
define('POP_APPLICATIONTAXONOMIES_DIR', dirname(__FILE__));

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
            define('POP_APPLICATIONTAXONOMIES_INITIALIZED', true);
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
