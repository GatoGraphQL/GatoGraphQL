<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_MultipleFileGenerator_Bundles {

    public function generate() {
        
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // Generate the bundle file with all the resources inside?
        if ($bundles = $resource_mapping['bundles']) {

            global $pop_resourceloader_bundlefilegenerator;
            foreach ($bundles as $bundleId => $resources_item) {

                $pop_resourceloader_bundlefilegenerator->set_filename($bundleId);
                $pop_resourceloader_bundlefilegenerator->set_extension('.js');
                $pop_resourceloader_bundlefilegenerator->set_resources($resources_item);
                $pop_resourceloader_bundlefilegenerator->generate();
            }
        }
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_multiplefilegenerator_bundles;
$pop_resourceloader_multiplefilegenerator_bundles = new PoP_ResourceLoader_MultipleFileGenerator_Bundles();
