<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Domain
Description: Implementation of domain features for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_DOMAIN_VERSION', 0.162);
define('POP_DOMAIN_DIR', dirname(__FILE__));

class PoP_Domain
{
    public function __construct()
    {

        // Priority: after PoP CDN
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888419);
        // \PoP\Root\App::getHookManager()->addAction('PoP:system-generate', array($this,'systemGenerate'));
    }
    public function init()
    {
        define('POP_DOMAIN_URL', plugins_url('', __FILE__));

        define('POP_DOMAIN_CONTENT_DIR', POP_CONTENT_DIR.'/pop-domain');
        define('POP_DOMAIN_CONTENT_URL', POP_CONTENT_URL.'/pop-domain');

        if ($this->validate()) {
            $this->initialize();
            define('POP_DOMAIN_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_Domain_Validation();
        return $validation->validate();
    }
    // function systemGenerate() {

    //     require_once 'installation.php';
    //     $installation = new PoP_Domain_Installation();
    //     return $installation->systemGenerate();
    // }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_Domain_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_Domain();
