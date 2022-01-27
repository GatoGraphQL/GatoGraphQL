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
define('POP_CDNFOUNDATION_VERSION', 0.125);
define('POP_CDNFOUNDATION_DIR', dirname(__FILE__));

class PoP_CDNFoundation
{
    public function __construct()
    {

        // Priority: after PoP Engine, and before everything else (except the "website-environment" plug-ins),
        // so we can set the POP_CDNFOUNDATION_ASSETS_URI constant in plugin_url before all other plug-ins need it
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888110);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_CDNFOUNDATION_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CDNFoundation_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CDNFoundation_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CDNFoundation();
