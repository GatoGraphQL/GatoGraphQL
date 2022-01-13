<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Server-Side Rendering
Description: Implementation of SSR capabilities for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SSR_VERSION', 0.157);
define('POP_SSR_DIR', dirname(__FILE__));
// define ('POP_SSR_VENDOR_DIR', POP_SSR_DIR.'/vendor/zordius/lightncandy');
define('POP_SSR_PHPTEMPLATES_DIR', POP_SSR_DIR.'/php-templates/compiled');

class PoP_SSR
{
    public function __construct()
    {

        // Priority: after PoP Engine Web Platform, inner circle
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888402);
    }
    public function init()
    {
        define('POP_SSR_URL', plugins_url('', __FILE__));
        if ($this->validate()) {
            $this->initialize();
            define('POP_SSR_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SSR_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SSR_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SSR();
