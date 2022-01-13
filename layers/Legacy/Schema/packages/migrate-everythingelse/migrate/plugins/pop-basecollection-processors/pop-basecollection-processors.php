<?php
/*
Plugin Name: PoP Base Collection Processors
Version: 0.1
Description: Plug-in providing the base processors for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_BASECOLLECTIONPROCESSORS_VERSION', 0.212);
define('POP_BASECOLLECTIONPROCESSORS_DIR', dirname(__FILE__));
define('POP_BASECOLLECTIONPROCESSORS_PHPTEMPLATES_DIR', POP_BASECOLLECTIONPROCESSORS_DIR.'/php-templates/compiled');

class PoP_BaseCollectionProcessors
{
    public function __construct()
    {

        // Priority: after PoP Engine Processors
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888610);
    }
    public function init()
    {
        define('POP_BASECOLLECTIONPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_BASECOLLECTIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_BaseCollectionProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_BaseCollectionProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_BaseCollectionProcessors();
