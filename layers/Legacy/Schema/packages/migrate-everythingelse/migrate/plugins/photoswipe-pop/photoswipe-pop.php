<?php
/*
Plugin Name: PhotoSwipe for PoP
Version: 0.1
Description: PhotoSwipe for the Platform of Platforms (PoP)
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

define('PHOTOSWIPEPOP_VERSION', 0.109);
define('PHOTOSWIPEPOP_VENDORRESOURCESVERSION', 0.100);
define('PHOTOSWIPEPOP_PHOTOSWIPE_VERSION', '4.1.2');
define('PHOTOSWIPEPOP_DIR', dirname(__FILE__));

class PhotoSwipe_PoP
{
    public function __construct()
    {

        // Priority: after PoP Application Web Platform
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888510);
    }

    public function init()
    {
        define('PHOTOSWIPEPOP_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('PHOTOSWIPEPOP_INITIALIZED', true);
        }
    }

    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PhotoSwipe_PoP_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PhotoSwipe_PoP_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PhotoSwipe_PoP();
