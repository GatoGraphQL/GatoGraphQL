<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_ResourcesMirrorCode extends PoP_Engine_FileReproductionBase {

    private $resources;

    function setResources($resources) {

        $this->resources = $resources;
    }

    function get_renderer() {

        global $pop_resourceloader_mirrorcode_renderer;
        return $pop_resourceloader_mirrorcode_renderer;
    }

    public function get_js_path() {
        
        return POP_RESOURCELOADER_ASSETS_DIR.'/js/jobs/mirrorcode.js';
    }

    function is_json_replacement() {

        return false;
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        global $pop_resourceloaderprocessor_manager;

        $contents = array();
        foreach ($this->resources as $resource) {

            // Get the content for that resource
            // Comment Leo 13/11/2017: use get_file_path instead of get_asset_path so that it includes the minified resources
            // $file = $pop_resourceloaderprocessor_manager->get_asset_path($resource);
            $file = $pop_resourceloaderprocessor_manager->get_file_path($resource);
            $file_contents = file_get_contents($file);
            if ($file_contents !== false) {
                $contents[] = $file_contents;
            }
        }
        $configuration['$contents'] = implode(PHP_EOL, $contents);

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ResourceLoader_FileReproduction_ResourcesMirrorCode();
