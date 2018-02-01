<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_UserAvatar_ServiceWorkers_Hooks_Locales {

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

            // Add all the locales other than the one already added through wp_enqueue_script
            $current = pop_useravatar_get_locale_jsfile();
            foreach (glob(POP_USERAVATAR_DIR."/js/locales/fileupload/*") as $file) {
                $fileurl = str_replace(POP_USERAVATAR_DIR, POP_USERAVATAR_URL, $file);
                if ($fileurl != $current) {
                    $precache[] = $fileurl;
                }
            }
        }
        
        return $precache;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_ServiceWorkers_Hooks_Locales();
