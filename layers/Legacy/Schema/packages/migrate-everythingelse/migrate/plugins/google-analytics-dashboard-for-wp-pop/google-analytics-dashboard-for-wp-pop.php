<?php
/*
Plugin Name: Google Analytics Dashboard for WP for PoP
Version: 0.1
Description: Integration with plug-in Google Analytics Dashboard for WP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('GADWP_POP_VERSION', 0.110);
define('GADWP_POP_DIR', dirname(__FILE__));

class GADWP_PoP
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_GoogleAnalytics_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP Google Analytics
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888520);
    }
    public function getProviderValidationClass($class)
    {
        return GADWP_PoP_Validation::class;
    }

    public function init()
    {
        define('GADWP_POP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('GADWP_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('GADWPManager');
        return true;
        // require_once 'validation.php';
        $validation = new GADWP_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new GADWP_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new GADWP_PoP();
