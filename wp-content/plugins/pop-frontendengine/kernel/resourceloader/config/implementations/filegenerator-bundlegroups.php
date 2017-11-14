<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_MultipleFileGenerator_BundleGroups {

    public function generate() {
        
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // Generate the bundlegroup file with all the resources inside?
        if ($bundlegroups = $resource_mapping['bundle-groups']) {

            global $pop_resourceloader_bundlegroupfilegenerator;
            $bundles = $resource_mapping['bundles'];
            foreach ($bundlegroups as $bundleGroupId => $resourcebundles) {
                
                $resources_item = array();
                foreach ($resourcebundles as $bundleId) {
                    $resources_item = array_merge(
                        $resources_item,
                        $bundles[$bundleId]
                    );
                }
                $pop_resourceloader_bundlegroupfilegenerator->set_filename($bundleGroupId);
                $pop_resourceloader_bundlegroupfilegenerator->set_extension('.js');
                $pop_resourceloader_bundlegroupfilegenerator->set_resources($resources_item);
                $pop_resourceloader_bundlegroupfilegenerator->generate();
            }
        }
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_multiplefilegenerator_bundlegroups;
$pop_resourceloader_multiplefilegenerator_bundlegroups = new PoP_ResourceLoader_MultipleFileGenerator_BundleGroups();
