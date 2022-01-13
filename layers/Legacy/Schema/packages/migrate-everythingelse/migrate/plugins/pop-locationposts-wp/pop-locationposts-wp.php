<?php
/*
Plugin Name: PoP Location Posts WordPress
Version: 0.1
Description: Implementation of PoP Location Posts WordPress
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTSWP_VERSION', 0.106);
define('POP_LOCATIONPOSTSWP_DIR', dirname(__FILE__));

define('POP_LOCATIONPOSTSWP__FILE__', __FILE__);
define('POP_LOCATIONPOSTSWP_BASE', plugin_basename(POP_LOCATIONPOSTSWP__FILE__));

class PoP_LocationPostsWP
{
    public function __construct()
    {
        // Priority: after PoP Locations
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888352);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTSWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostsWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostsWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostsWP();
