<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_ServiceWorkers_Hooks_Fonts {

    function __construct() {
        
        add_filter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'get_precache_list'),
            10,
            2
        );
    }

    function get_precache_list($precache, $resourceType) {

        if ($resourceType == 'static') {

            // Add all the fonts
            $precache[] = get_wassup_font_url();
            $precache[] = get_fontawesome_font_url();

            // When generating the SW file, the $enqueuefile_type will be always "resource", then we must pre-cache the font twice: once for "resource", once for the chosen $enqueuefile_type
            if (!PoP_Frontend_ServerUtils::access_externalcdn_resources() && PoP_Frontend_ServerUtils::use_code_splitting() && PoP_Frontend_ServerUtils::loading_bundlefile(true)) {

                $precache[] = get_wassup_font_url('bundlefile');
                $precache[] = get_fontawesome_font_url('bundlefile');
            }
        }
        
        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ServiceWorkers_Hooks_Fonts();
