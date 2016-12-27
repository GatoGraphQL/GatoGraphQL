<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_Hooks_Checkpoints {

    function __construct() {

        add_filter(
            'PoP_ServiceWorkers_Job_Fetch:exclude',
            array($this, 'get_excluded_paths'),
            10,
            2
        );

        $resourceType = 'json';
        add_filter(
            'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith',
            array($this, 'get_networkfirst_json_paths')
        );
    }

    function get_networkfirst_json_paths($paths) {
        
        // It is basically all pages configured as silent_document which are not appshell and do not have any checkpoint (as in, no user state required)
        // Eg: POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS and all the other loaders
        // All the files with a checkpoint must not be cached
        $pages = array();
        global $gd_template_settingsprocessor_manager, $gd_template_settingsmanager;
        foreach ($gd_template_settingsprocessor_manager->get_processors() as $settingsprocessor) {
            if ($silents = $settingsprocessor->silent_document(GD_SETTINGS_HIERARCHY_PAGE)) {
                
                // Remove those pages which are appshell
                $appshells = $settingsprocessor->is_appshell(GD_SETTINGS_HIERARCHY_PAGE);
                if (!$appshells) {
                    $appshells = array();
                }
                $pages = array_merge(
                    $pages,
                    array_diff(
                        array_keys($silents),
                        array_keys($appshells)
                    )
                );
            }
        }

        // Filter all the pages which have no checkpoints
        foreach ($pages as $page) {
            $checkpoint_settings = $gd_template_settingsmanager->get_page_checkpoints($page);
            if (!$checkpoint_settings['type']) {
                $paths[] = get_permalink($page);
            }
        }
        
        return $paths;
    }

    function get_excluded_paths($excluded, $resourceType) {
        
        // Exclude all the dynamic pages: those needing user state
        if ($resourceType == 'json') {

            // Only if the type if GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER. The Static type can be cached since it contains no data
            // Type GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_NULL was added as to reject NEVERCACHE pages, even though they need no checkpoint validation (Eg: Notifications)
            $dynamic_types = array(
                GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
                GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_NULL,
            );
            
            // All the files with a checkpoint must not be cached
            global $gd_template_settingsprocessor_manager;
            foreach ($gd_template_settingsprocessor_manager->get_processors() as $settingsprocessor) {
                foreach ($settingsprocessor->get_checkpoints(GD_SETTINGS_HIERARCHY_PAGE) as $page => $settings) {

                    // The ID might've not been defined for that page (eg: Projects in TPP Debate), so skip it
                    if (!$page) continue;
                    if (in_array($settings['type'], $dynamic_types)) {
                    
                        $excluded[] = get_permalink($page);
                    }
                }
            }
        }
        
        return $excluded;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_Hooks_Checkpoints();
