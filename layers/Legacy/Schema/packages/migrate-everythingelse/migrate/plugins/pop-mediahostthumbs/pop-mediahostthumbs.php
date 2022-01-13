<?php
/*
Plugin Name: Media Host Thumbs for PoP
Version: 0.1
Description: Configuration for external websites: Collection of default thumbs for the Links section; non-embeddable websites.
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MEDIAHOSTTHUMBS_VERSION', 0.131);

class PoP_MediaHostThumbs
{
    public function __construct()
    {

        // Priority: after PoP Add Post Links
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888370);
    }
    public function init()
    {
        if ($this->validate()) {
            $this->initialize();
            define('POP_MEDIAHOSTTHUMBS_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_MediaHostThumbs_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MediaHostThumbs_Initialization();
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MediaHostThumbs();
