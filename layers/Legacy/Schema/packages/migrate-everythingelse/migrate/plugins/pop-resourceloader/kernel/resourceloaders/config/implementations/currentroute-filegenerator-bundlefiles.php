<?php

class PoP_ResourceLoader_CurrentRouteFileGenerator_BundleFiles extends PoP_ResourceLoader_FileGenerator_BundleFilesBase {

    protected function getResourceMapping() {

        $options = array(
	        'from-current-vars' => true,
	    );
	    // componentFilter is null => loadingSite
        return PoP_ResourceLoader_FileReproduction_Utils::getResourceMapping(null, $options);
    }
}
    
/**
 * Initialize
 */
global $pop_resourceloader_currentroute_filegenerator_bundlefiles;
$pop_resourceloader_currentroute_filegenerator_bundlefiles = new PoP_ResourceLoader_CurrentRouteFileGenerator_BundleFiles();
