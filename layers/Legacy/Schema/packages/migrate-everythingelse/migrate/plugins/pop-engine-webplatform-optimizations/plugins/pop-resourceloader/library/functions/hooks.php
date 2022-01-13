<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_FrontEndOptimization_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
            array($this, 'getManagerDependencies')
        );
    }

    public function getManagerDependencies($dependencies)
    {

        // Hook in the settings values into the JSON
        // Comment Leo 19/11/2017: if we enable the "config" param, then add this resource always
        // (Otherwise it fails because the configuration is cached but the list of modules to load is different)
        // If not, then add it if we are generating the resources on runtime
        if (PoP_WebPlatformEngineOptimizations_ServerUtils::extractResponseIntoJsfilesOnRuntime()) {
            $dependencies[] = [PoP_FrontEndOptimization_JSResourceLoaderProcessor::class, PoP_FrontEndOptimization_JSResourceLoaderProcessor::RESOURCE_INITIALIZEDATA];
        }
        
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_FrontEndOptimization_ResourceLoaderProcessor_Hooks();
