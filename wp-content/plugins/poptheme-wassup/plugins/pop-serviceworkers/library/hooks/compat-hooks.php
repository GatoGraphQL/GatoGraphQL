<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_ServiceWorkers_Hooks_Compat {

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

            // Add the IE 8 and below compatibility files
            $precache = array_merge(
                $precache,
                get_compatibility_js_files()
            );
        }

        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ServiceWorkers_Hooks_Compat();
