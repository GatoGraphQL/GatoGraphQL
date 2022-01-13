<?php
/*
Plugin Name: PoP CDN Foundation
Description: Foundation over which CDN capabitilies can be added to PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CDNFOUNDATIONWP_VERSION', 0.125);
define('POP_CDNFOUNDATIONWP_DIR', dirname(__FILE__));

class PoP_CDNFoundationWP
{
    public function __construct()
    {
        // Priority: after PoP Engine, and before everything else (except the "website-environment" plug-ins),
        // so we can set the POP_CDNFOUNDATIONWP_ASSETS_URI constant in plugin_url before all other plug-ins need it
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888112);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CDNFOUNDATIONWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CDNFoundationWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CDNFoundationWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CDNFoundationWP();
