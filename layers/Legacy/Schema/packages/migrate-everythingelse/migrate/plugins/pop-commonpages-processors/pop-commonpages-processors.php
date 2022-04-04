<?php
/*
Plugin Name: PoP Common Pages Processors
Version: 0.1
Description: Processors for different Sections for the Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_COMMONPAGESPROCESSORS_VERSION', 0.108);
define('POP_COMMONPAGESPROCESSORS_DIR', dirname(__FILE__));

class PoP_CommonPagesProcessors
{
    public function __construct()
    {

        // Priority: after ...
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888891);
    }

    public function init()
    {
        define('POP_COMMONPAGESPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_COMMONPAGESPROCESSORS_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_CommonPagesProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_CommonPagesProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_CommonPagesProcessors();
