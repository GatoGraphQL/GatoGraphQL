<?php
/*
Plugin Name: PoP Common User Roles Web Platform
Description: Implementation of Common User Roles Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONUSERROLESWEBPLATFORM_VERSION', 0.132);
define('POP_COMMONUSERROLESWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_COMMONUSERROLESWEBPLATFORM_PHPTEMPLATES_DIR', POP_COMMONUSERROLESWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_CommonUserRolesWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP User Communities Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888580);
    }
    public function init()
    {
        define('POP_COMMONUSERROLESWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONUSERROLESWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonUserRolesWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonUserRolesWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CommonUserRolesWebPlatform();
