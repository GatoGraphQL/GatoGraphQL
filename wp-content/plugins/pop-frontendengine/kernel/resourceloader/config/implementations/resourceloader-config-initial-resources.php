<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_InitialResourcesConfig extends PoP_ResourceLoader_FileReproduction_AddResourcesConfigBase {

	function get_renderer() {

        global $pop_resourceloader_initialresources_configfile_renderer;
        return $pop_resourceloader_initialresources_configfile_renderer;
    }

    protected function match_hierarchy() {

        return 'page';
    }

    protected function match_paths() {

        global $gd_dataquery_manager;
        
        // This shall provide an array with the following pages:
        // 1. get_backgroundload_pages:
            // POP_COREPROCESSORS_PAGE_LOADERS_INITIALFRAMES
        // 2. get_noncacheablepages:
            // POP_WPAPI_PAGE_LOADERS_POSTS_FIELDS
            // POP_WPAPI_PAGE_LOADERS_USERS_FIELDS
            // POP_WPAPI_PAGE_LOADERS_COMMENTS_FIELDS
            // POP_WPAPI_PAGE_LOADERS_TAGS_FIELDS
        // 3. get_cacheablepages:
            // POP_WPAPI_PAGE_LOADERS_POSTS_LAYOUTS
            // POP_WPAPI_PAGE_LOADERS_USERS_LAYOUTS
            // POP_WPAPI_PAGE_LOADERS_COMMENTS_LAYOUTS
            // POP_WPAPI_PAGE_LOADERS_TAGS_LAYOUTS
        $pages = array_merge(
            array_keys(PoP_Frontend_ConfigurationUtils::get_backgroundload_pages()),
            $gd_dataquery_manager->get_cacheablepages(),
            $gd_dataquery_manager->get_noncacheablepages()
        );
        
        // Added through hooks:
        // 4. Logged-in User data page
        // Allow to hook in page POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA
        $pages = array_filter(array_values(apply_filters(
            'PoP_ResourceLoader_FileReproduction_InitialResourcesConfig:pages',
            $pages
        )));
        
        // Get the paths for all those pages
        $paths = array();
        foreach ($pages as $page_id) {
            $paths[] = trailingslashit(GD_TemplateManager_Utils::get_page_path($page_id));
        }
        return $paths;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ResourceLoader_FileReproduction_InitialResourcesConfig();
