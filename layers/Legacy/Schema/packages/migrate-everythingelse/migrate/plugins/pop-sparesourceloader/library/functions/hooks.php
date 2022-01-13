<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_FrontEnd_SPAResourceLoaderProcessor_Hooks
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
        $dependencies[] = [PoP_FrontEnd_DynamicJSSPAResourceLoaderProcessor::class, PoP_FrontEnd_DynamicJSSPAResourceLoaderProcessor::RESOURCE_SPARESOURCELOADERCONFIG];
        $dependencies[] = [PoP_FrontEnd_DynamicJSSPAResourceLoaderProcessor::class, PoP_FrontEnd_DynamicJSSPAResourceLoaderProcessor::RESOURCE_SPARESOURCELOADERCONFIG_RESOURCES];
        
        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_FrontEnd_SPAResourceLoaderProcessor_Hooks();
