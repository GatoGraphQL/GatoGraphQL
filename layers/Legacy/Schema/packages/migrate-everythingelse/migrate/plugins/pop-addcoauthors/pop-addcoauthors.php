<?php
/*
Plugin Name: PoP Add Coauthors
Version: 0.1
Description: The foundation for a PoP Add Coauthors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDCOAUTHORS_VERSION', 0.106);
define('POP_ADDCOAUTHORS_DIR', dirname(__FILE__));

class PoP_AddCoauthors
{
    public function __construct()
    {

        // Priority: after PoP User Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888340);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDCOAUTHORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddCoauthors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddCoauthors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddCoauthors();
