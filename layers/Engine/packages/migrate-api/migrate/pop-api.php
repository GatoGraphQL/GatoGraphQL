<?php
namespace PoP\API;
use PoP\Hooks\Facades\HooksAPIFacade;

class Plugin
{
    public function __construct()
    {
        // Allow the Theme to override definitions.
        // Priority: new section, after PoP CMS section
        HooksAPIFacade::getInstance()->addAction('plugins_loaded', array($this, 'init'), 30);
    }
    public function init()
    {
        require_once 'library/load.php';
    }
}

/**
 * Initialization
 */
new Plugin();
