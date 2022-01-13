<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;
/*
Plugin Name: PoP Multidomain for SPA Resource Loader
Description: Implementation of the multi-domain features for PoP SPA Resource Loader
Plugin URI: https://getpop.org
Version: 0.1
Author: Leonardo Losovizen/u/leo/
*/

//-------------------------------------------------------------------------------------
// Constants Definition
//-------------------------------------------------------------------------------------
define('POP_MULTIDOMAINSPARESOURCELOADER_VERSION', 0.162);
define('POP_MULTIDOMAINSPARESOURCELOADER_DIR', dirname(__FILE__));
// define ('POP_MULTIDOMAINSPARESOURCELOADER_ASSETS_DIR', POP_MULTIDOMAINSPARESOURCELOADER_DIR.'/plugins/pop-resourceloader/library/resourceloader-config/assets');

class PoP_MultiDomainSPAResourceLoader
{
    public function __construct()
    {

        // Priority: after PoP Multidomain
        \PoP\Root\App::getHookManager()->addAction('plugins_loaded', array($this, 'init'), 888430);
    }
    public function init()
    {
        define('POP_MULTIDOMAINSPARESOURCELOADER_URL', plugins_url('', __FILE__));

        if ($this->validate()) {
            $this->initialize();
            define('POP_MULTIDOMAINSPARESOURCELOADER_INITIALIZED', true);
        }
    }
    public function validate()
    {
        return true;
        include_once 'validation.php';
        $validation = new PoP_MultiDomainSPAResourceLoader_Validation();
        return $validation->validate();
    }
    public function initialize()
    {
        include_once 'initialization.php';
        $initialization = new PoP_MultiDomainSPAResourceLoader_Initialization();
        ;
        return $initialization->initialize();
    }
}

/**
 * Initialization
 */
new PoP_MultiDomainSPAResourceLoader();
