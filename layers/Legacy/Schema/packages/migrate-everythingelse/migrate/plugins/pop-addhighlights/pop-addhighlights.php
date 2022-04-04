<?php
/*
Plugin Name: PoP Add Highlights
Version: 0.1
Description: The foundation for a PoP Add Highlights
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDHIGHLIGHTS_VERSION', 0.106);
define('POP_ADDHIGHLIGHTS_DIR', dirname(__FILE__));

class PoP_AddHighlights
{
    public function __construct()
    {
        // Priority: after PoP Add Related Posts
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888370);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDHIGHLIGHTS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddHighlights_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddHighlights_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddHighlights();
