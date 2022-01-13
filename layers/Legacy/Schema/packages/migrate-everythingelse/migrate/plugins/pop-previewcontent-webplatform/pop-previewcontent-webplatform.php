<?php
/*
Plugin Name: Public Post Preview for PoP Web Platform
Version: 0.1
Description: Integration with plug-in Public Post Preview for PoP
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('POP_PREVIEWCONTENTWEBPLATFORM_VERSION', 0.109);
define('POP_PREVIEWCONTENTWEBPLATFORM_DIR', dirname(__FILE__));

class PoP_PreviewContentWebPlatform
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('POP_PREVIEWCONTENTWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_PREVIEWCONTENTWEBPLATFORM_INITIALIZED', true);
        }
    }

    public function validate()
    {
        // This is a different case than the norm!
        // Only load if plugin is active!
        return class_exists('DS_Public_Post_Preview');
        return true;
        include_once 'validation.php';
        $validation = new PoP_PreviewContentWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_PreviewContentWebPlatform_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_PreviewContentWebPlatform();
