<?php

class PoPTheme_Wassup_Core_ServiceWorkers_ResourceLoader_Hooks_Resources
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'getPrecacheList'),
            10,
            2
        );
    }

    public function getPrecacheList($precache, $resourceType)
    {
        if ($resourceType == 'static') {
            // When generating the SW file, the $enqueuefile_type will be always "resource", then we must pre-cache the font twice: once for "resource", once for the chosen $enqueuefile_type
            if (!PoP_WebPlatform_ServerUtils::accessExternalcdnResources() && PoP_ResourceLoader_ServerUtils::useCodeSplitting() && PoP_ResourceLoader_ServerUtils::loadingBundlefile(true)) {
                $precache[] = getBootstrapFontUrl('bundlefile');
            }
        }

        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_Core_ServiceWorkers_ResourceLoader_Hooks_Resources();
