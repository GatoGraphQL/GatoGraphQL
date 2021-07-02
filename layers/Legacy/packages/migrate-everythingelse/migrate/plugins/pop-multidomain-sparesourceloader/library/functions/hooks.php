<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_MultiDomainSPAResourceLoader_ResourceLoaderProcessor_Hooks
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

        // If accessing the External page, then we must load the resource-config.js and initialresources.js for the external domain
        $vars = ApplicationState::getVars();
        // if ($vars['external-url-domain']) {
        if ($vars['routing-state']['is-standard'] && $vars['route'] == POP_MULTIDOMAIN_ROUTE_EXTERNAL) {
            $dependencies[] = [PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::class, PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL];
            $dependencies[] = [PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::class, PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES];
        }

        return $dependencies;
    }
}

/**
 * Initialization
 */
new PoP_MultiDomainSPAResourceLoader_ResourceLoaderProcessor_Hooks();
