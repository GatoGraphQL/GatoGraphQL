<?php
/*
Plugin Name: PoP Trending Tags
Version: 0.1
Description: The foundation for a PoP Trending Tags
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\TrendingTags;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TRENDINGTAGS_VERSION', 0.106);
define('POP_TRENDINGTAGS_DIR', dirname(__FILE__));

class Plugins
{
    public function __construct()
    {

        // Priority: new section, after PoP Tags
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888210);
    }
    public function version($version)
    {
        return POP_TRENDINGTAGS_VERSION;
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_TRENDINGTAGS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugins();
