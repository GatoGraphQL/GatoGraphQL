<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Scripts {

    function __construct() {

        // Priority 100: after wp-hooks.php adds the scripts
        add_filter(
            'PoP_ServiceWorkers_Job_CacheResources:precache',
            array($this, 'get_precache_list'),
            100,
            2
        );
    }

    function get_precache_list($precache, $resourceType) {

        if ($resourceType == 'static') {

            // The Google Maps .js produces an error, so remove it
            // Error: Fetch API cannot load https://maps.google.com/maps/api/js?key={$KEY}. No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access. If an opaque response serves your needs, set the request's mode to 'no-cors' to fetch the resource with CORS disabled.
            $pos = array_search(get_googlemaps_url(), $precache);
            if ($pos !== false) {
                array_splice($precache, $pos, 1);
            }
        }

        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Core_ServiceWorkers_Hooks_Scripts();
