<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP User Communities Web Platform
Description: Implementation of User Communities Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_USERCOMMUNITIESWEBPLATFORM_VERSION', 0.132);
define('POP_USERCOMMUNITIESWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_USERCOMMUNITIESWEBPLATFORM_PHPTEMPLATES_DIR', POP_USERCOMMUNITIESWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_UserCommunitiesWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Social Network Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888570);
    }
    public function init()
    {
        define('POP_USERCOMMUNITIESWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_USERCOMMUNITIESWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_UserCommunitiesWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_UserCommunitiesWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_UserCommunitiesWebPlatform();
