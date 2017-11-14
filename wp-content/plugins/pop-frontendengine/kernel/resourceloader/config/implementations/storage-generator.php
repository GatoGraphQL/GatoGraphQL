<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_StorageGenerator {

    public function generate() {
        
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // $generate_bundles = PoP_Frontend_ServerUtils::generate_bundle_files();
        // $generate_bundlegroups = PoP_Frontend_ServerUtils::generate_bundlegroup_files();

        // Save the bundle-ids under their cachename, so no need to calculate it again when accessing a page
        global $pop_resourceloader_generatedfilesstoragemanager;
        foreach ($resource_mapping['resources']['js']['flat'] as $hierarchy => $key_bundlegroup) {

            foreach ($key_bundlegroup as $keyId => $bundleGroupId) {

                // When generating the bundle(group)s, the key is the cache name
                $cachename = array_search($keyId, $resource_mapping['keys']);

                // // Save the bundlegroups?
                // if ($generate_bundlegroups) {
                $pop_resourceloader_generatedfilesstoragemanager->add_bundlegroup_ids($cachename, array($bundleGroupId));
                // }

                $bundle_ids = $resource_mapping['bundle-groups'][$bundleGroupId];
                // // Save the bundles?
                // if ($generate_bundles) {
                $pop_resourceloader_generatedfilesstoragemanager->add_bundle_ids($cachename, $bundle_ids);
                // }

                // Save the resources
                $resources = array();
                foreach ($bundle_ids as $bundleId) {

                    $resources = array_merge(
                        $resources,
                        $resource_mapping['bundles'][$bundleId]
                    );
                }
                $pop_resourceloader_generatedfilesstoragemanager->add_resources($cachename, $resources);

            }
        }
        foreach ($resource_mapping['resources']['js']['path'] as $hierarchy => $path_key_bundlegroup) {

            foreach ($path_key_bundlegroup as $path => $key_bundlegroup) {

                foreach ($key_bundlegroup as $keyId => $bundleGroupId) {

                    // When generating the bundle(group)s, the key is the cache name
                    $cachename = array_search($keyId, $resource_mapping['keys']);

                    // // Save the bundlegroups?
                    // if ($generate_bundlegroups) {
                    $pop_resourceloader_generatedfilesstoragemanager->add_bundlegroup_ids($cachename, array($bundleGroupId));
                    // }

                    $bundle_ids = $resource_mapping['bundle-groups'][$bundleGroupId];
                    // // Save the bundles?
                    // if ($generate_bundles) {
                    $pop_resourceloader_generatedfilesstoragemanager->add_bundle_ids($cachename, $bundle_ids);
                    // }

                    // Save the resources
                    $resources = array();
                    foreach ($bundle_ids as $bundleId) {

                        $resources = array_merge(
                            $resources,
                            $resource_mapping['bundles'][$bundleId]
                        );
                    }
                    $pop_resourceloader_generatedfilesstoragemanager->add_resources($cachename, $resources);
                }
            }
        }
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloader_storagegenerator;
$pop_resourceloader_storagegenerator = new PoP_ResourceLoader_StorageGenerator();
