<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Theme Wassup Web Platform
Description: Implementation of Theme Wassup Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POPTHEME_WASSUPWEBPLATFORM_VERSION', 0.132);
define('POPTHEME_WASSUPWEBPLATFORM_DIR', dirname(__FILE__));
define('POPTHEME_WASSUPWEBPLATFORM_PHPTEMPLATES_DIR', POPTHEME_WASSUPWEBPLATFORM_DIR.'/php-templates/compiled');
define('POPTHEME_WASSUPWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);

class PoP_ThemeWassupWebPlatform
{
    public function __construct()
    {

        // Priority: after everything
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888599);
    }
    public function init()
    {
        define('POPTHEME_WASSUPWEBPLATFORM_URL', plugins_url('', __FILE__));

        define('POPTHEME_WASSUPWEBPLATFORM_THEMECUSTOMIZATION_ASSETDESTINATION_DIR', POP_CONTENT_DIR.'/theme-custom');
        define('POPTHEME_WASSUPWEBPLATFORM_THEMECUSTOMIZATION_ASSETDESTINATION_URL', POP_CONTENT_URL.'/theme-custom');

        if ($this->validate()) {
            $this->initialize();
            define('POPTHEME_WASSUPWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_ThemeWassupWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_ThemeWassupWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_ThemeWassupWebPlatform();
