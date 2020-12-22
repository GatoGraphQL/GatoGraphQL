<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoPTheme_Wassup_ServiceWorkers_Hooks_Fonts
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
            // Add all the fonts
            $precache[] = getWassupFontUrl();
            $precache[] = getFontawesomeFontUrl();
        }
        
        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_ServiceWorkers_Hooks_Fonts();
