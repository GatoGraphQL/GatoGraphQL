<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP CDN WordPress
Description: Implementation of the CDN for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CDNWP_VERSION', 0.157);
define('POP_CDNWP_DIR', dirname(__FILE__));

class PoP_CDNWP
{
    public function __construct()
    {
        // Priority: after PoP Engine Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888412);
    }
    public function init()
    {
        define('POP_CDNWP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CDNWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CDNWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CDNWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CDNWP();
