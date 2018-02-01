<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_CurrentRouteFileGenerator_BundleFiles extends PoP_ResourceLoader_FileGenerator_BundleFilesBase {

    protected function get_resource_mapping() {

        $options = array(
	        'from-current-vars' => true,
	    );
        return PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false, $options);
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_currentroute_filegenerator_bundlefiles;
$pop_resourceloader_currentroute_filegenerator_bundlefiles = new PoP_ResourceLoader_CurrentRouteFileGenerator_BundleFiles();
