<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_MultipleFileGenerator_BundleGroups {

    public function generate() {
        
        global $pop_resourceloader_jsbundlegroupfilegenerator, $pop_resourceloader_cssbundlegroupfilegenerator;

        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // Generate the bundlegroup file with all the resources inside?
        // $type = 'js' or 'css'
        foreach ($resource_mapping['bundle-groups'] as $type => $bundlegroups) {

            $bundles = $resource_mapping['bundles'][$type];
            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                $filegenerator = $pop_resourceloader_jsbundlegroupfilegenerator;
            }
            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                $filegenerator = $pop_resourceloader_cssbundlegroupfilegenerator;
            }
            foreach ($bundlegroups as $bundleGroupId => $resourcebundles) {
                
                $resources_item = array();
                foreach ($resourcebundles as $bundleId) {
                    $resources_item = array_merge(
                        $resources_item,
                        $bundles[$bundleId]
                    );
                }
                $filegenerator->set_filename($bundleGroupId);
                $filegenerator->set_extension('.'.$type);
                $filegenerator->set_resources($resources_item);
                $filegenerator->generate();
            }
        }
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_multiplefilegenerator_bundlegroups;
$pop_resourceloader_multiplefilegenerator_bundlegroups = new PoP_ResourceLoader_MultipleFileGenerator_BundleGroups();
