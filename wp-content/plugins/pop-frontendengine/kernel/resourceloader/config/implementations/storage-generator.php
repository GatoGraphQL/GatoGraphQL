<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoader_StorageGenerator {

    // Generate and Save the file containing what resources/bundle/bundlegroups were generated for each cachename
    public function generate() {
        
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::get_resource_mapping(false);

        // $generate_bundles = PoP_Frontend_ServerUtils::generate_bundle_files();
        // $generate_bundlegroups = PoP_Frontend_ServerUtils::generate_bundlegroup_files();

        // Save the bundle-ids under their cachename, so no need to calculate it again when accessing a page
        global $pop_resourceloader_generatedfilesstoragemanager;
        $types = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS,
            POP_RESOURCELOADER_RESOURCETYPE_CSS,
        );
        foreach ($types as $type) {

            foreach ($resource_mapping['resources'][$type]['flat'] as $hierarchy => $key_bundlegroups) {

                foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {

                    foreach ($bundleGroupIds as $bundleGroupId) {

                        // When generating the bundle(group)s, the key is the cache name
                        $cachename = array_search($keyId, $resource_mapping['keys']);

                        // // Save the bundlegroups?
                        // if ($generate_bundlegroups) {
                        if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                            $pop_resourceloader_generatedfilesstoragemanager->add_js_bundlegroup_ids($cachename, array($bundleGroupId));
                        }
                        elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                            $pop_resourceloader_generatedfilesstoragemanager->add_css_bundlegroup_ids($cachename, array($bundleGroupId));
                        }
                        // }

                        $bundle_ids = $resource_mapping['bundle-groups'][$type][$bundleGroupId];
                        // // Save the bundles?
                        // if ($generate_bundles) {
                        if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                            $pop_resourceloader_generatedfilesstoragemanager->add_js_bundle_ids($cachename, $bundle_ids);
                        }
                        elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                            $pop_resourceloader_generatedfilesstoragemanager->add_css_bundle_ids($cachename, $bundle_ids);
                        }
                        // }

                        // Save the resources
                        $resources = array();
                        foreach ($bundle_ids as $bundleId) {

                            $resources = array_merge(
                                $resources,
                                $resource_mapping['bundles'][$type][$bundleId]
                            );
                        }
                        if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                            $pop_resourceloader_generatedfilesstoragemanager->add_js_resources($cachename, $resources);
                        }
                        elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                            $pop_resourceloader_generatedfilesstoragemanager->add_css_resources($cachename, $resources);
                        }
                    }

                }
            }
            foreach ($resource_mapping['resources'][$type]['path'] as $hierarchy => $path_key_bundlegroup) {

                foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {

                    foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {

                        foreach ($bundleGroupIds as $bundleGroupId) {

                            // When generating the bundle(group)s, the key is the cache name
                            $cachename = array_search($keyId, $resource_mapping['keys']);

                            // // Save the bundlegroups?
                            // if ($generate_bundlegroups) {
                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                $pop_resourceloader_generatedfilesstoragemanager->add_js_bundlegroup_ids($cachename, array($bundleGroupId));
                            }
                            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                $pop_resourceloader_generatedfilesstoragemanager->add_css_bundlegroup_ids($cachename, array($bundleGroupId));
                            }
                            // }

                            $bundle_ids = $resource_mapping['bundle-groups'][$type][$bundleGroupId];
                            // // Save the bundles?
                            // if ($generate_bundles) {
                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                $pop_resourceloader_generatedfilesstoragemanager->add_js_bundle_ids($cachename, $bundle_ids);
                            }
                            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                $pop_resourceloader_generatedfilesstoragemanager->add_css_bundle_ids($cachename, $bundle_ids);
                            }
                            // }

                            // Save the resources
                            $resources = array();
                            foreach ($bundle_ids as $bundleId) {

                                $resources = array_merge(
                                    $resources,
                                    $resource_mapping['bundles'][$type][$bundleId]
                                );
                            }
                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                $pop_resourceloader_generatedfilesstoragemanager->add_js_resources($cachename, $resources);
                            }
                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                $pop_resourceloader_generatedfilesstoragemanager->add_css_resources($cachename, $resources);
                            }
                        }
                    }
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
