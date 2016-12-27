<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_Hooks_WPFormatting {

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

            // Code copied from wp-includes/formatting.php
            global $wp_version;
            $version = 'ver=' . $wp_version;
            $precache[] = includes_url( "js/wp-emoji-release.min.js?$version" );
        }

        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_Hooks_WPFormatting();
