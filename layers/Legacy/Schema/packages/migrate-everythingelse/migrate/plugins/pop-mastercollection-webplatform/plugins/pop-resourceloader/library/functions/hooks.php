<?php

use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_CoreProcessors_ResourceLoaderProcessor_Hooks
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

        // This dependency has been added, because we are referencing function 'mentions_source' in editor.php
        // Even though popMentions will be loaded from the internal/external method calls, if the mapping has not
        // been generated, then it will fail and give a JS error. By expliciting the dependency here, it will work always fine
        $dependencies[] = [PoP_CoreProcessors_ResourceLoaderProcessor::class, PoP_CoreProcessors_ResourceLoaderProcessor::RESOURCE_MENTIONS];

        // Comment Leo 19/11/2017: load the appshell file if either: enabling the config, using the appshell, or allowing for Service Workers, which use the appshell to load content when offline
        if (PoP_WebPlatform_ServerUtils::useAppshell() || (defined('POP_SERVICEWORKERS_INITIALIZED') && !PoP_ServiceWorkers_ServerUtils::disableServiceworkers())) {
            $dependencies[] = [PoP_CoreProcessors_ResourceLoaderProcessor::class, PoP_CoreProcessors_ResourceLoaderProcessor::RESOURCE_APPSHELL];
        }
        
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_CoreProcessors_ResourceLoaderProcessor_Hooks();
