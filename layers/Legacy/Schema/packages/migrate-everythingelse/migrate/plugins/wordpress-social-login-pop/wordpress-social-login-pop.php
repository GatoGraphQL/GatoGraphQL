<?php
/*
Plugin Name: Wordpress Social Login for PoP
Version: 0.1
Description: Wordpress Social Login for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('WSL_POP_VERSION', 0.107);
define('WSL_POP_DIR', dirname(__FILE__));

/**
 * Includes: load it initially, as to override the functions from WSL
 */
require_once 'includes/load.php';

class WSL_PoP
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_SocialLogin_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP Social Login
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888370);
    }
    public function getProviderValidationClass($class)
    {
        return WSL_PoP_Validation::class;
    }

    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('WSL_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return function_exists('wsl_activate');
        return true;
        // require_once 'validation.php';
        $validation = new WSL_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new WSL_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new WSL_PoP();
