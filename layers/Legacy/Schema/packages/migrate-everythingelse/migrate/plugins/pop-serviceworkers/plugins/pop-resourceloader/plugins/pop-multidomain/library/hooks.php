<?php

class PoP_ServiceWorkers_ResourceLoader_MultiDomain_RemoveResources
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration:registerScripts',
            $this->modifyResources(...)
        );
    }

    public function modifyResources($resources)
    {

        // Do not add these resources to the Service Workers file:
        // POP_RESOURCELOADER_RESOURCELOADERCONFIG_EXTERNALRESOURCES
        // (These only make sense to be added on the External page)
        $resources = array_values(
            array_diff(
                $resources,
                array(
                    [PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::class, PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::RESOURCE_RESOURCELOADERCONFIG_EXTERNAL],
                    [PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::class, PoP_MultiDomainSPAResourceLoader_DynamicJSResourceLoaderProcessor::RESOURCE_RESOURCELOADERCONFIG_EXTERNALRESOURCES],
                )
            )
        );

        return $resources;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_ResourceLoader_MultiDomain_RemoveResources();
