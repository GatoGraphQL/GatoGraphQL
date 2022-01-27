<?php

class PoPTheme_Wassup_ServiceWorkers_Hooks_Compat
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'getPrecacheList'),
            10,
            2
        );
    }

    public function getPrecacheList($precache, $resourceType)
    {
        if ($resourceType == 'static') {
            // Add the IE 8 and below compatibility files
            $precache = array_merge(
                $precache,
                getCompatibilityJsFiles()
            );
        }

        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoPTheme_Wassup_ServiceWorkers_Hooks_Compat();
