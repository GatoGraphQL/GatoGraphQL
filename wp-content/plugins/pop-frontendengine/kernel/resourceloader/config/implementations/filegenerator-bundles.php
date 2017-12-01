<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_MultipleFileGenerator_Bundles {

    public function generate() {
        
        global $pop_resourceloader_jsbundlefilegenerator, $pop_resourceloader_cssbundlefilegenerator;
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // Calculate the $cachename under which each bundle was generated
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
                        $bundle_ids = $resource_mapping['bundle-groups'][$type][$bundleGroupId];
                        foreach ($bundle_ids as $bundleId) {
                            $cachenames[$bundleId] = $cachename;
                        }
                    }
                }
            }
            foreach ($resource_mapping['resources'][$type]['path'] as $hierarchy => $path_key_bundlegroup) {
                foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {
                    foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {
                        // When generating the bundle(group)s, the key is the cache name
                        $cachename = array_search($keyId, $resource_mapping['keys']);
                        foreach ($bundleGroupIds as $bundleGroupId) {
                            $bundle_ids = $resource_mapping['bundle-groups'][$type][$bundleGroupId];
                            foreach ($bundle_ids as $bundleId) {
                                $cachenames[$bundleId] = $cachename;
                            }
                        }
                    }
                }
            }
        }

        // Generate the bundle file with all the resources inside?
        // $type = 'js' or 'css'
        foreach ($resource_mapping['bundles'] as $type => $bundles) {

            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                $filegenerator = $pop_resourceloader_jsbundlefilegenerator;
            }
            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                $filegenerator = $pop_resourceloader_cssbundlefilegenerator;
            }
            foreach ($bundles as $bundleId => $resources_item) {

                $filegenerator->set_cachename($cachenames[$bundleId]);
                $filegenerator->set_filename($bundleId);
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
global $pop_resourceloader_multiplefilegenerator_bundles;
$pop_resourceloader_multiplefilegenerator_bundles = new PoP_ResourceLoader_MultipleFileGenerator_Bundles();
