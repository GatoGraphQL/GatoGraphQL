<?php
/*
Plugin Name: PoP Social Media Providers Processors
Description: Implementation of Social Media Providers Processors for PoP
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_SOCIALMEDIAPROVIDERSPROCESSORS_VERSION', 0.132);
define('POP_SOCIALMEDIAPROVIDERSPROCESSORS_DIR', dirname(__FILE__));

class PoP_SocialMediaProvidersProcessors
{
    public function __construct()
    {

        // Priority: after PoP Application Processors
        \PoP\Root\App::addAction('plugins_loaded', array($this, 'init'), 888810);
    }
    public function init()
    {
        define('POP_SOCIALMEDIAPROVIDERSPROCESSORS_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_SOCIALMEDIAPROVIDERSPROCESSORS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_SocialMediaProvidersProcessors_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_SocialMediaProvidersProcessors_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_SocialMediaProvidersProcessors();
