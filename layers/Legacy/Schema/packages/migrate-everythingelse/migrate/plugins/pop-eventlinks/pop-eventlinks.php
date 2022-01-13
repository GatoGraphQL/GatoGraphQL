<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Event Links
Description: Implementation of Event Links for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_EVENTLINKS_VERSION', 0.132);
define('POP_EVENTLINKS_DIR', dirname(__FILE__));

class PoP_EventLinks
{
    public function __construct()
    {

        // Priority: after PoP Events
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888380);
    }
    public function init()
    {
        define('POP_EVENTLINKS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_EVENTLINKS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_EventLinks_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_EventLinks_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_EventLinks();
