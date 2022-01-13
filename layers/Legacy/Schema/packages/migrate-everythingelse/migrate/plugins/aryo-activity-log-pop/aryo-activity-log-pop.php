<?php
/*
Plugin Name: Activity Log for PoP
Version: 0.1
Description: Activity Log for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('AAL_POP_VERSION', 0.107);
define('AAL_POP_DIR', dirname(__FILE__));

define('AAL_POP_LOG__FILE__', __FILE__);
define('AAL_POP_LOG_BASE', plugin_basename(AAL_POP_LOG__FILE__));

class AAL_PoP
{
    public function __construct()
    {

        // The maintenance must be executed before 'load_plugins' or 'init' take place, so place it here
        // https://codex.wordpress.org/Function_Reference/register_activation_hook
        include_once 'maintenance/load.php';

        include_once 'validation.php';
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_Notifications_Validation:provider-validation-class',
            array($this, 'getProviderValidationClass')
        );

        // Priority: after PoP Notifications
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888350);
    }
    public function getProviderValidationClass($class)
    {
        return AAL_PoP_Validation::class;
    }

    public function init()
    {
        if ($this->validate()) {
            // Initialize plugin
            $this->initialize();

            // Set as initialized
            define('AAL_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('AAL_Main');
        return true;
        // require_once 'validation.php';
        $validation = new AAL_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new AAL_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new AAL_PoP();
