<?php
/*
Plugin Name: PoP Location Post Links Creation
Version: 0.1
Description: The foundation for a PoP Location Post Links Creation
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTLINKSCREATION_VERSION', 0.106);
define('POP_LOCATIONPOSTLINKSCREATION_DIR', dirname(__FILE__));

class PoP_LocationPostLinksCreation
{
    public function __construct()
    {

        // Priority: after PoP Location Posts Creation
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888370);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTLINKSCREATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostLinksCreation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostLinksCreation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinksCreation();
