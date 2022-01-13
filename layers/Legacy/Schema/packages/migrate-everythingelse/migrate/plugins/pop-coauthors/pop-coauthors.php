<?php
/*
Plugin Name: PoP Coauthors
Version: 0.1
Description: The foundation for a PoP Coauthors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COAUTHORS_VERSION', 0.106);
define('POP_COAUTHORS_DIR', dirname(__FILE__));

class PoP_Coauthors
{
    public function __construct()
    {

        // Priority: after PoP Application
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888310);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_COAUTHORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('coauthors_plus');
        return true;
        include_once 'validation.php';
        $validation = new PoP_Coauthors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Coauthors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Coauthors();
