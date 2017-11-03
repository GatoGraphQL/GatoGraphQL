<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_Config extends PoP_Engine_FileReproductionBase {

    function get_renderer() {

        global $pop_resourceloader_configfile_renderer;
        return $pop_resourceloader_configfile_renderer;
    }

    public function get_js_path() {
        
        return POP_RESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config.js';
    }
    
	public function get_configuration() {
        
        $configuration = parent::get_configuration();

        // Domain
        $configuration['$domain'] = get_site_url();
        // $configuration['$pathStartPos'] = strlen(trailingslashit(get_bloginfo('url')));

        // Get the list of all categories, and then their paths
        $categories = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => false,
            'fields' => 'ids',
        ));
        $single_paths = array_map(array('GD_TemplateManager_Utils', 'get_category_path'), $categories);

        // Allow EM to add their own paths
        $single_paths = apply_filters(
            'PoP_ResourceLoader_FileReproduction_Config:configuration:category-paths',
            $single_paths
        );

        // Path slugs
        global $wp_rewrite;
        $configuration['$paths'] = array(
            'author' => $wp_rewrite->author_base.'/',
            'tag' => get_option('tag_base').'/',
            'single' => $single_paths,
        );

        global $pop_resourceloader_hierarchyformatcombinationresources_configfile_generator;
        $configFileURLPlaceholder = 
            $pop_resourceloader_hierarchyformatcombinationresources_configfile_generator->get_url()
            .'/'
            .$pop_resourceloader_hierarchyformatcombinationresources_configfile_generator->get_variable_filename('{0}', '{1}');
        $configFileURLPlaceholder = add_query_arg('ver', pop_version(), $configFileURLPlaceholder);
        $configuration['$configFileURLPlaceholder'] = $configFileURLPlaceholder;

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ResourceLoader_FileReproduction_Config();
