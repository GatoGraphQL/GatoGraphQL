<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_ServiceWorkers_ResourceLoaderProcessor_Hooks
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

        // Comment Leo 14/11/2017: enqueue scripts only if we enable SW by configuration,
        // or if allowing for the config so it does not explode due to the different resources in the bundlegroup
        if (!PoP_ServiceWorkers_ServerUtils::disableServiceworkers()) {
            // Comment Leo 14/11/2017: we must execute /sw.js before executing /sw-registrar.js
            // Otherwise, when first loading the page, if it has changed, it will not show the message "Please click here to reload the page",
            // since the code to execute that is executed after the SW 'refresh' function...
            $dependencies[] = [PoP_ServiceWorkers_JSResourceLoaderProcessor::class, PoP_ServiceWorkers_JSResourceLoaderProcessor::RESOURCE_SW];

            // Add sw-registrar.js
            $dependencies[] = [PoP_ServiceWorkers_DynamicJSResourceLoaderProcessor::class, PoP_ServiceWorkers_DynamicJSResourceLoaderProcessor::RESOURCE_SWREGISTRAR];
        }
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_ResourceLoaderProcessor_Hooks();
