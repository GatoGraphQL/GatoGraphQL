<?php

class PoP_ServiceWorkers_ResourceLoader_MultiDomain_CDN_RemoveResources
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_ServiceWorkers_WebPlatform_ResourceLoader_ScriptsAndStylesRegistration:registerScripts',
            array($this, 'modifyResources')
        );
    }

    public function modifyResources($resources)
    {

        // Do not add these resources to the Service Workers file:
        // POP_RESOURCELOADER_CDNCONFIG_EXTERNAL
        // (These only make sense to be added on the External page)
        $resources = array_values(
            array_diff(
                $resources,
                array(
                    [PoP_MultiDomain_CDN_DynamicJSResourceLoaderProcessor::class, PoP_MultiDomain_CDN_DynamicJSResourceLoaderProcessor::RESOURCE_CDNCONFIG_EXTERNAL],
                )
            )
        );

        return $resources;
    }
}

/**
 * Initialization
 */
new PoP_ServiceWorkers_ResourceLoader_MultiDomain_CDN_RemoveResources();
