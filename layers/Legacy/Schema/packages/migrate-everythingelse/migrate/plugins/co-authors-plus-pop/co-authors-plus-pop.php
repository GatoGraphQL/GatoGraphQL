<?php
/*
Plugin Name: Co-Authors Plus for PoP
Version: 0.1
Description: Integration with plug-in Co-Authors Plus for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

define('CAP_POP_VERSION', 0.110);
define('CAP_POP_DIR', dirname(__FILE__));

class CAP_PoP
{
    public function __construct()
    {
        include_once 'validation.php';
        \PoP\Root\App::addFilter(
            'PoP_Coauthors_Validation:provider-validation-class',
            $this->getProviderValidationClass(...)
        );
        \PoP\Root\App::addFilter(
            'PoP_AddCoauthors_Validation:provider-validation-class',
            $this->getProviderValidationClass(...)
        );

        // Priority: after PoP Add Coauthors
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888350);
    }
    public function getProviderValidationClass($class)
    {
        return CAP_PoP_Validation::class;
    }

    public function init()
    {
        define('CAP_POP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('CAP_POP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('coauthors_plus');
        return true;
        // require_once 'validation.php';
        $validation = new CAP_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new CAP_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new CAP_PoP();
