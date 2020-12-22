<?php
/*
Plugin Name: Public Post Preview for PoP
Version: 0.1
Description: Integration with plug-in Public Post Preview for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

define('PPP_POP_VERSION', 0.110);
define('PPP_POP_DIR', dirname(__FILE__));

class PPP_PoP
{
    public function __construct()
    {
        include_once 'validation.php';
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_PreviewContent_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP Preview Content
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 360);
    }
    public function getProviderValidationClass($class)
    {
        return PPP_PoP_Validation::class;
    }

    public function init()
    {
        define('PPP_POP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('PPP_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('DS_Public_Post_Preview');
        return true;
        // require_once 'validation.php';
        $validation = new PPP_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PPP_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PPP_PoP();
