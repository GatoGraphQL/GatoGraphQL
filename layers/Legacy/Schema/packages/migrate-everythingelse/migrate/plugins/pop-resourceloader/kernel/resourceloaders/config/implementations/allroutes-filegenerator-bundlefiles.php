<?php

class PoP_ResourceLoader_AllRoutesFileGenerator_BundleFiles extends PoP_ResourceLoader_FileGenerator_BundleFilesBase {

    protected function getResourceMapping() {

        // componentFilter is null => loadingSite
        return PoP_ResourceLoader_FileReproduction_Utils::getResourceMapping(null);
    }

    protected function generateBundleFiles() {

        return PoP_ResourceLoader_ServerUtils::generateBundleFiles();
    }

    protected function generateBundlegroupFiles() {

        return PoP_ResourceLoader_ServerUtils::generateBundlegroupFiles();
    }
}
    
/**
 * Initialize
 */
global $pop_resourceloader_allroutes_filegenerator_bundlefiles;
$pop_resourceloader_allroutes_filegenerator_bundlefiles = new PoP_ResourceLoader_AllRoutesFileGenerator_BundleFiles();
