<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_ServiceWorkers_Hooks_WPFormatting
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
            // Code copied from wp-includes/formatting.php
            global $wp_version;
            $version = 'ver=' . $wp_version;
            $precache[] = includes_url("js/wp-emoji-release.min.js?$version");
        }

        return $precache;
    }
}
    
/**
 * Initialize
 */
new PoP_ServiceWorkers_Hooks_WPFormatting();
