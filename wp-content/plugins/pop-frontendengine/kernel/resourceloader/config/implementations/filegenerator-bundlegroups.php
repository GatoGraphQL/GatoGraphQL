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

        // Calculate the $cachename under which each bundlegroup was generated
        // This information will be needed to access the corresponding $noncritical_resources 
        // for calculating which files can be set as "defer"
        $cachenames = array();
        $types = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS,
            POP_RESOURCELOADER_RESOURCETYPE_CSS,
        );
        foreach ($types as $type) {
            foreach ($resource_mapping['resources'][$type]['flat'] as $hierarchy => $key_bundlegroups) {
                foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                    // When generating the bundle(group)s, the key is the cache name
                    $cachename = array_search($keyId, $resource_mapping['keys']);
                    foreach ($bundleGroupIds as $bundleGroupId) {
                        $cachenames[$bundleGroupId] = $cachename;
                    }
                }
            }
            foreach ($resource_mapping['resources'][$type]['path'] as $hierarchy => $path_key_bundlegroup) {
                foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {
                    foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                        // When generating the bundle(group)s, the key is the cache name
                        $cachename = array_search($keyId, $resource_mapping['keys']);
                        foreach ($bundleGroupIds as $bundleGroupId) {                            
                            $cachenames[$bundleGroupId] = $cachename;
                        }
                    }
                }
            }
        }

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
                $filegenerator->set_cachename($cachenames[$bundleGroupId]);
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
