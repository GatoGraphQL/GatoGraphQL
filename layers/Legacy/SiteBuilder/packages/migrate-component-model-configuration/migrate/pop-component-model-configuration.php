<?php
/*
Plugin Name: PoP Configuration Engine
Version: 0.1
Description: Front-end module for the Platform of Platforms
Plugin URI: https://getpop.org/
Author: Leonardo Losoviz
*/
namespace PoP\ConfigurationComponentModel;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_CONFIGURATIONCOMPONENTMODEL_VERSION', 0.160);
define('POP_CONFIGURATIONCOMPONENTMODEL_DIR', dirname(__FILE__));

class Plugin
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 88825);
    }
    public function init()
    {
        // Allow other plug-ins to modify the plugins_url path (eg: pop-aws adding the CDN)
        define('POP_CONFIGURATIONCOMPONENTMODEL_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_CONFIGURATIONCOMPONENTMODEL_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoPEngineConfiguration_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new Initialization();;
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new Plugin();
