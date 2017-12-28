<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_RejectedPageHooks {

    function __construct() {

        add_filter(
            'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:startsWith:partial',
            array($this, 'get_rejected_partialpaths')
        );
    }

    function get_rejected_partialpaths($rejected) {
        
        // Exclude all the dynamic pages: those needing user state

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
                
                    $rejected[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page));
                }
            }
        }
        
        return $rejected;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_RejectedPageHooks();
