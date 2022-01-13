<?php
/*
Plugin Name: PoP Add Highlights WordPress
Version: 0.1
Description: The foundation for a PoP Add Highlights WordPress
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ADDHIGHLIGHTSWP_VERSION', 0.106);
define('POP_ADDHIGHLIGHTSWP_DIR', dirname(__FILE__));

define('POP_ADDHIGHLIGHTSWP__FILE__', __FILE__);
define('POP_ADDHIGHLIGHTSWP_BASE', plugin_basename(POP_ADDHIGHLIGHTSWP__FILE__));

class PoP_AddHighlightsWP
{
    public function __construct()
    {
        // Priority: after PoP Add Related Posts
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888372);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_ADDHIGHLIGHTSWP_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_AddHighlightsWP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_AddHighlightsWP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_AddHighlightsWP();
