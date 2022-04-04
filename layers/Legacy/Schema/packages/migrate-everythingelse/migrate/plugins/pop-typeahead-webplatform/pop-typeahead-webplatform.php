<?php
/*
Plugin Name: PoP Typeahead Web Platform
Description: Implementation of Typeahead Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_TYPEAHEADWEBPLATFORM_VERSION', 0.132);
define('POP_TYPEAHEADWEBPLATFORM_VENDORRESOURCESVERSION', 0.100);
define('POP_TYPEAHEADWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_TYPEAHEADWEBPLATFORM_PHPTEMPLATES_DIR', POP_TYPEAHEADWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_TypeaheadWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888510);
    }
    public function init()
    {
        define('POP_TYPEAHEADWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_TYPEAHEADWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_TypeaheadWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_TypeaheadWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_TypeaheadWebPlatform();
