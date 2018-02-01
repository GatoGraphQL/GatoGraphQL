<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_ServiceWorkers_Hooks_Resources {

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

            // Add all the images from this plug-in
            foreach (glob(POPTHEME_WASSUP_DIR."/img/*") as $file) {
                $precache[] = str_replace(POPTHEME_WASSUP_DIR, POPTHEME_WASSUP_URL, $file);
            }

            // Add all the images from the active theme
            $theme_dir = get_stylesheet_directory();
            $theme_uri = get_stylesheet_directory_uri();
            foreach (glob($theme_dir."/img/*") as $file) {
                $precache[] = str_replace($theme_dir, $theme_uri, $file);
            }
        }
        
        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ServiceWorkers_Hooks_Resources();
