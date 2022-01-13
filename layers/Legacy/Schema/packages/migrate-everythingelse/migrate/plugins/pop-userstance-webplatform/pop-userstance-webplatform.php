<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User Stance Web Platform
Description: Implementation of User Stance Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERSTANCEWEBPLATFORM_VERSION', 0.132);
define('POP_USERSTANCEWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_USERSTANCEWEBPLATFORM_PHPTEMPLATES_DIR', POP_USERSTANCEWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_UserStanceWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP User Platform Web Platform
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888540);
    }
    public function init()
    {
        define('POP_USERSTANCEWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERSTANCEWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserStanceWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserStanceWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserStanceWebPlatform();
