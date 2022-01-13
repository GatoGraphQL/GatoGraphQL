<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Multidomain
Description: Implementation of the multi-domain features for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MULTIDOMAIN_VERSION', 0.162);
define('POP_MULTIDOMAIN_DIR', dirname(__FILE__));

class PoP_MultiDomain
{
    public function __construct()
    {

        // Priority: after PoP CDN
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888420);
        // HooksAPIFacade::getInstance()->addAction('PoP:system-generate', array($this,'systemGenerate'));
    }
    public function init()
    {
        define('POP_MULTIDOMAIN_URL', plugins_url('', __FILE__));

        define('POP_MULTIDOMAIN_CONTENT_DIR', POP_CONTENT_DIR.'/pop-multidomain');
        define('POP_MULTIDOMAIN_CONTENT_URL', POP_CONTENT_URL.'/pop-multidomain');

        if ($this->validate()) {
            $this->initialize();
            define('POP_MULTIDOMAIN_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_MultiDomain_Validation();
        return $validation->validate();
    }
    // function systemGenerate() {

    //     require_once 'installation.php';
    //     $installation = new PoP_MultiDomain_Installation();
    //     return $installation->systemGenerate();
    // }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MultiDomain_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MultiDomain();
