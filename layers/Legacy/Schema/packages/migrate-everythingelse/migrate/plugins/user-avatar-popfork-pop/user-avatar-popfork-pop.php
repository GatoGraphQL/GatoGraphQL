<?php
/*
Plugin Name: User Avatar for PoP
Version: 0.1
Description: Implementation of the User Avatar plugin for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('USERAVATARPOP_VERSION', 0.110);
define('USERAVATARPOP_DIR', dirname(__FILE__));

class UserAvatarPoPForkPoP
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_Avatar_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP Avatar
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888320);
    }
    public function getProviderValidationClass($class)
    {
        return UserAvatarPoPForkPoP_Validation::class;
    }
    public function init()
    {
        define('USERAVATARPOP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('USERAVATARPOP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('user_avatar_init');
        return true;
        // require_once 'validation.php';
        $validation = new UserAvatarPoPForkPoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new UserAvatarPoPForkPoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new UserAvatarPoPForkPoP();
