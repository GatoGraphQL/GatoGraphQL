<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_ServiceWorkers_ResourceLoader_Hooks_Fonts
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
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
                $precache[] = getWassupFontUrl('bundlefile');
                $precache[] = getFontawesomeFontUrl('bundlefile');
            }
        }
        
        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_ServiceWorkers_ResourceLoader_Hooks_Fonts();
