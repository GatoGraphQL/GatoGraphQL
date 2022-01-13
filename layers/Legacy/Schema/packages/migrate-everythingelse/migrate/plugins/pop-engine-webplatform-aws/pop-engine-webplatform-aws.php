<?php
/*
Plugin Name: PoP Web Platform Engine for AWS
Version: 0.1
Description: Use AWS for the Engine Web Platform plugin for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEWEBPLATFORM_AWS_VERSION', 0.106);
define('POP_ENGINEWEBPLATFORM_AWS_DIR', dirname(__FILE__));

class PoP_WebPlatformEngine_AWS
{
    public function __construct()
    {

        // Priority: after PoP CDN
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888420);
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ENGINEWEBPLATFORM_AWS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_WebPlatformEngine_AWS_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_WebPlatformEngine_AWS_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_WebPlatformEngine_AWS();
