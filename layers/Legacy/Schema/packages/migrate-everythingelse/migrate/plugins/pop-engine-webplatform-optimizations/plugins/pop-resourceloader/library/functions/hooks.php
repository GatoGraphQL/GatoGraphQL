<?php

use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;

class PoP_FrontEndOptimization_ResourceLoaderProcessor_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
            $this->getManagerDependencies(...)
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
