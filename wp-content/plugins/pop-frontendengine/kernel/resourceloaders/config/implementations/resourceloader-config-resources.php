<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_ResourcesConfig extends PoP_ResourceLoader_FileReproduction_ResourcesConfigBase {

	protected function get_options() {

		$options = parent::get_options();

		// Calculate the resources for the current request already, to make sure we don't call the function with $vars modified
		if (PoP_Frontend_ServerUtils::skip_loadingframe_resources()) {
			
		    // To obtain the list of all resources that are always loaded, we can simply calculate the resources for this actual request,
            // for page /generate-theme/ (POP_SYSTEM_PAGE_SYSTEM_GENERATETHEME), which because it doesn't add blocks or anything to the output,
            // it is strip of extra stuff, making it the minimum loading experience
            $options['ignore-resources'] = PoPFrontend_ResourceLoader_ScriptsAndStylesUtils::get_loadingframe_resources();
		}
        
        return $options;
    }

    function get_renderer() {

        global $pop_resourceloader_resources_configfile_renderer;
        return $pop_resourceloader_resources_configfile_renderer;
    }

    public function get_assets_path() {
        
        return POP_RESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config-setresources.js';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ResourceLoader_FileReproduction_ResourcesConfig();
