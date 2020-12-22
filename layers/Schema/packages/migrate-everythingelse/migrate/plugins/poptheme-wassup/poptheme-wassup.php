<?php
/*
Plugin Name: PoP Theme: Wassup
Version: 0.1
Description: Wassup Theme for PoP—Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Hooks\Facades\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POPTHEME_WASSUP_VERSION', 0.177);

define('POPTHEME_WASSUP_DIR', dirname(__FILE__));
define('POPTHEME_WASSUP_TEMPLATES', POPTHEME_WASSUP_DIR.'/templates');
define('POPTHEME_WASSUP_PLUGINS', POPTHEME_WASSUP_DIR.'/plugins');
define('POPTHEME_WASSUP_VENDORRESOURCESVERSION', 0.100);

class PoPTheme_Wassup
{
    public function __construct()
    {

        /**
         * WP Overrides
         */
        include_once dirname(__FILE__).'/wp-includes/load.php';

        // Priority: new section, after PoP Application Processors section
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 850);
    }

    public function init()
    {
        define('POPTHEME_WASSUP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POPTHEME_WASSUP_INITIALIZED', true);
        } else {
            define('POP_STARTUP_INITIALIZED', false);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoPTheme_Wassup_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoPTheme_Wassup_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoPTheme_Wassup();
