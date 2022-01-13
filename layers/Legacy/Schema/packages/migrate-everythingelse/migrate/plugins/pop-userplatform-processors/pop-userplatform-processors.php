<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User Platform Processors
Description: Implementation of User Platform Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERPLATFORMPROCESSORS_VERSION', 0.132);
define('POP_USERPLATFORMPROCESSORS_DIR', dirname(__FILE__));
define('POP_USERPLATFORMPROCESSORS_PHPTEMPLATES_DIR', POP_USERPLATFORMPROCESSORS_DIR.'/php-templates/compiled');

class PoP_UserPlatformProcessors
{
    public function __construct()
    {

        // Priority: after PoP User Login Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888830);
    }
    public function init()
    {
        define('POP_USERPLATFORMPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERPLATFORMPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserPlatformProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserPlatformProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserPlatformProcessors();
