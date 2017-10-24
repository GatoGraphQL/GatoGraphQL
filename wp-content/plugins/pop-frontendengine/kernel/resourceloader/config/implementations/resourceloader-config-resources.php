<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_ResourcesConfig extends PoP_ResourceLoader_FileReproduction_ResourcesConfigBase {

    function get_renderer() {

        global $pop_resourceloader_resources_configfile_renderer;
        return $pop_resourceloader_resources_configfile_renderer;
    }

    public function get_js_path() {
        
        return POP_RESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config-setresources.js';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ResourceLoader_FileReproduction_ResourcesConfig();
