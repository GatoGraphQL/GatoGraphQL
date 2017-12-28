<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_Hooks_Checkpoints {

    function __construct() {

        add_filter(
            'PoP_ServiceWorkers_Job_Fetch:exclude:partial',
            array($this, 'get_excluded_partialpaths'),
            10,
            2
        );

        $resourceType = 'json';
        add_filter(
            'PoP_ServiceWorkers_Job_Fetch:strategies:'.$resourceType.':networkFirst:startsWith:partial',
            array($this, 'get_networkfirst_json_partialpaths')
        );

        // // Priority 100: execute after 'pop_sw_add_etag_header'
        // add_filter(
        //     'PoP_Engine:output_json:add_etag_header', 
        //     array($this, 'add_etag_header'),
        //     100
        // );
        add_filter(
            'pop_sw_add_etag_header', 
            array($this, 'add_etag_header')
        );
    }

    function add_etag_header($add) {

        // If this is a silent page, then no need to add the etag, because none of the silent pages has strategy Cache First
        // function pop_sw_add_etag_header() already addresses !GD_TemplateManager_Utils::page_requires_user_state(), however
        // this doesn't include pages POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS and POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS, etc, which
        // do not require the user state, however they don't need the ETag either
        global $gd_template_settingsmanager;
        if ($add && $gd_template_settingsmanager->silent_document()) {

            return false;
        }
        
        return $add;
    }

    function get_networkfirst_json_partialpaths($paths) {
        
        // It is basically all pages configured as silent_document which are not appshell and do not have any checkpoint (as in, no user state required)
        // Eg: POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS and all the other loaders
        // It is so because these pages are loaded in the background, and are secondary (eg: loading comments),
        // so there's no need to show the stuff immediately to the user, when it might be stale. Instead, go get the 
        // latest data from the server, show cached data only if the user has no connection
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
                // $paths[] = get_permalink($page);
                $paths[] = GD_TemplateManager_Utils::get_page_path($page);
            }
        }
        
        return $paths;
    }

    function get_excluded_partialpaths($excluded, $resourceType) {
        
        // Exclude all the dynamic pages: those needing user state
        if ($resourceType == 'json') {

            // Only if the type if GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER. The Static type can be cached since it contains no data
            // Type GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER was added as to reject NEVERCACHE pages, even though they need no checkpoint validation (Eg: Notifications)
            $dynamic_types = array(
                GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
                GD_DATALOAD_NOCHECKPOINTVALIDATION_TYPE_DATAFROMSERVER,
            );
            
            // All the files with a checkpoint must not be cached
            global $gd_template_settingsprocessor_manager;
            foreach ($gd_template_settingsprocessor_manager->get_processors() as $settingsprocessor) {
                
                $internals = $settingsprocessor->is_for_internal_use(GD_SETTINGS_HIERARCHY_PAGE);
                foreach ($settingsprocessor->get_checkpoints(GD_SETTINGS_HIERARCHY_PAGE) as $page => $settings) {

                    // The ID might've not been defined for that page (eg: Projects in TPP Debate), so skip it
                    // Skip also if it is an internal page, we don't want to expose it
                    if (!$page || $internals[$page]) continue;
                    if (in_array($settings['type'], $dynamic_types)) {
                    
                        $excluded[] = GD_TemplateManager_Utils::get_page_path($page);
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
