<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Location Post Links Processors
Description: Implementation of Location Post Links Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTLINKSPROCESSORS_VERSION', 0.132);
define('POP_LOCATIONPOSTLINKSPROCESSORS_DIR', dirname(__FILE__));

class PoP_LocationPostLinksProcessors
{
    public function __construct()
    {

        // // Priority: after PoP Location Posts Processors
        // HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888860);
        // Priority: after PoP Location Posts Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888900);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTLINKSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTLINKSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostLinksProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostLinksProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostLinksProcessors();
