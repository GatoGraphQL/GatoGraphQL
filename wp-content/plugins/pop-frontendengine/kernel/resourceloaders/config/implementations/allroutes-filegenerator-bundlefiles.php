<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_AllRoutesFileGenerator_BundleFiles extends PoP_ResourceLoader_FileGenerator_BundleFilesBase {

    protected function get_resource_mapping() {

        return PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);
    }

    protected function generate_bundle_files() {

        return PoP_Frontend_ServerUtils::generate_bundle_files();
    }

    protected function generate_bundlegroup_files() {

        return PoP_Frontend_ServerUtils::generate_bundlegroup_files();
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_allroutes_filegenerator_bundlefiles;
$pop_resourceloader_allroutes_filegenerator_bundlefiles = new PoP_ResourceLoader_AllRoutesFileGenerator_BundleFiles();
