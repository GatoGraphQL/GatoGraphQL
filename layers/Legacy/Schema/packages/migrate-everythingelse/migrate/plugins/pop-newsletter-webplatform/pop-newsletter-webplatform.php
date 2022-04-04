<?php
/*
Plugin Name: PoP Newsletter Web Platform
Description: Implementation of Newsletter Web Platform for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_NEWSLETTERWEBPLATFORM_VERSION', 0.132);
define('POP_NEWSLETTERWEBPLATFORM_DIR', dirname(__FILE__));
define('POP_NEWSLETTERWEBPLATFORM_PHPTEMPLATES_DIR', POP_NEWSLETTERWEBPLATFORM_DIR.'/php-templates/compiled');

class PoP_NewsletterWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP User Platform Web Platform
        \PoP\Root\App::addAction('plugins_loaded', $this->init(...), 888540);
    }
    public function init()
    {
        define('POP_NEWSLETTERWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_NEWSLETTERWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_NewsletterWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_NewsletterWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_NewsletterWebPlatform();
