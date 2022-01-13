<?php
/*
Plugin Name: PoP  Post Category Layouts Processors
Version: 0.1
Description: Implementation of processors for the  Post Category Layouts for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_POSTCATEGORYLAYOUTSPROCESSORS_VERSION', 0.111);
define('POP_POSTCATEGORYLAYOUTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_PostCategoryLayoutsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Category Posts Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888830);
    }
    public function init()
    {
        define('POP_POSTCATEGORYLAYOUTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_POSTCATEGORYLAYOUTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_PostCategoryLayoutsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PostCategoryLayoutsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PostCategoryLayoutsProcessors();
