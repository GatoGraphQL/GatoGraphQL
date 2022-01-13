<?php
/*
Plugin Name: PoP Engine Web Platform
Version: 0.1
Description: Front-end module for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_ENGINEWEBPLATFORM_VERSION', 0.160);
define('POP_ENGINEWEBPLATFORM_DIR', dirname(__FILE__));

class PoPWebPlatform
{
    public function __construct()
    {

        // Priority: new section, after PoP Application section
        HooksAPIFacade::getInstance()->addAction(
            'plugins_loaded',
            function() {
                if ($this->validate()) {
                    require_once 'platforms/load.php';
                }
            },
            392
        );
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 888400);
    }
    public function init()
    {
        // Allow other plug-ins to modify the plugins_url path (eg: pop-aws adding the CDN)
        define('POP_ENGINEWEBPLATFORM_URL', plugins_url('', __FILE__));

        if ($this->validate()) {

            $this->initialize();
            define('POP_ENGINEWEBPLATFORM_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoPWebPlatform_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        global $PoPWebPlatform_Initialization;
        return $PoPWebPlatform_Initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoPWebPlatform();
