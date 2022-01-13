<?php
/*
Plugin Name: PoP MultilingualProcessors
Version: 0.1
Description: The foundation for a PoP MultilingualProcessors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MULTILINGUALPROCESSORS_VERSION', 0.106);
define('POP_MULTILINGUALPROCESSORS_DIR', dirname(__FILE__));

class PoP_MultilingualProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888810);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_MULTILINGUALPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('QTX_Translator');
        return true;
        include_once 'validation.php';
        $validation = new PoP_MultilingualProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MultilingualProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MultilingualProcessors();
