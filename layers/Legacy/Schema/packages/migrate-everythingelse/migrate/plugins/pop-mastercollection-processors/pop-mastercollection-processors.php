<?php
/*
Plugin Name: PoP Master Collection Processors
Version: 0.1
Description: Implementation of PoP Master Collection Processors
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MASTERCOLLECTIONPROCESSORS_VERSION', 0.106);
define('POP_MASTERCOLLECTIONPROCESSORS_DIR', dirname(__FILE__));

class PoP_MasterCollectionProcessors
{
    public function __construct()
    {

        // Priority: new section, after PoP Base Collection Processors section
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888700);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_MASTERCOLLECTIONPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_MasterCollectionProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MasterCollectionProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MasterCollectionProcessors();
