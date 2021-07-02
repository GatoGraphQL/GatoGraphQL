<?php
use PoP\Hooks\Facades\HooksAPIFacade;
class PoP_ServiceWorkers_ResourceLoader_WebPlatformEngineOptimizations_Installation
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addAction('PoP:system-generate', array($this, 'systemGenerate'));
    }

    public function systemGenerate()
    {

        // Modify to load all the resources for the Service Workers
        new PoP_ServiceWorkers_WebPlatformEngineOptimization_ResourceLoader_Initialization();
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_ResourceLoader_WebPlatformEngineOptimizations_Installation();
