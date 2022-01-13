<?php
/*
Plugin Name: PoP Location Post Category Layouts Processors
Version: 0.1
Description: Implementation of processors for the Location Post Category Layouts for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_LOCATIONPOSTCATEGORYLAYOUTSPROCESSORS_VERSION', 0.111);
define('POP_LOCATIONPOSTCATEGORYLAYOUTSPROCESSORS_DIR', dirname(__FILE__));

class PoP_LocationPostCategoryLayoutsProcessors
{
    public function __construct()
    {

        // Priority: after PoP Location Post Processors
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888891);
    }
    public function init()
    {
        define('POP_LOCATIONPOSTCATEGORYLAYOUTSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_LOCATIONPOSTCATEGORYLAYOUTSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_LocationPostCategoryLayoutsProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_LocationPostCategoryLayoutsProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_LocationPostCategoryLayoutsProcessors();
