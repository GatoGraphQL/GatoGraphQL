<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_FileReproduction_AddResourcesConfigBase extends PoP_ResourceLoader_FileReproduction_ResourcesConfigBase {

    protected $fileurl;

	public function get_assets_path() {
        
        return POP_RESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config-addresources.js';
    }

    public function setFileURL($fileurl) {
        
        return $this->fileurl = $fileurl;
    }

    public function get_configuration() {
        
        $configuration = parent::get_configuration();

        // Add the fileurl
        $configuration['$fileurl'] = $this->fileurl;

        return $configuration;
    }
}