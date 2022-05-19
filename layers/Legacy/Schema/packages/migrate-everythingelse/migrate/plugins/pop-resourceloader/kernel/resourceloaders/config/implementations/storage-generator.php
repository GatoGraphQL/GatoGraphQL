<?php

class PoP_ResourceLoader_StorageGenerator {

    // Generate and Save the file containing what resources/bundle/bundlegroups were generated for each model_instance_id
    public function generate() {
        
        // componentFilter is null => loadingSite
        $resource_mapping = PoP_ResourceLoader_FileReproduction_Utils::getResourceMapping(null);

        // Save the bundle-ids under their model_instance_id, so no need to calculate it again when accessing a page
        global $pop_resourceloader_generatedfilesmanager;
        $types = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL,
                POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR,
                POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC,
                POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE,
            ),
            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL,
                POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR,
                POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC,
            ),
        );
        foreach ($types as $type => $subtypes) {
            foreach ($subtypes as $subtype) {

                foreach ($resource_mapping['resources'][$type][$subtype]['flat'] as $nature => $key_bundlegroups) {

                    foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {

                        // When generating the bundle(group)s, the key is the cache name
                        $model_instance_id = array_search($keyId, $resource_mapping['keys']);

                        foreach ($bundleGroupIds as $bundleGroupId) {

                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                $pop_resourceloader_generatedfilesmanager->setJsBundlegroupIds($model_instance_id, $subtype, array($bundleGroupId));
                            }
                            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                $pop_resourceloader_generatedfilesmanager->setCssBundlegroupIds($model_instance_id, $subtype, array($bundleGroupId));
                            }

                            $bundle_ids = $resource_mapping['bundle-groups'][$type][$subtype][$bundleGroupId];
                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                $pop_resourceloader_generatedfilesmanager->setJsBundleIds($model_instance_id, $subtype, $bundle_ids);
                            }
                            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                $pop_resourceloader_generatedfilesmanager->setCssBundleIds($model_instance_id, $subtype, $bundle_ids);
                            }

                            // Save the resources
                            $resources = array();
                            foreach ($bundle_ids as $bundleId) {

                                $resources = array_unique(
                                    array_merge(
                                        $resources,
                                        $resource_mapping['bundles'][$type][$subtype][$bundleId]
                                    ),
                                    SORT_REGULAR
                                );
                            }
                            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                $pop_resourceloader_generatedfilesmanager->setJsResources($model_instance_id, $subtype, $resources);
                            }
                            elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                $pop_resourceloader_generatedfilesmanager->setCssResources($model_instance_id, $subtype, $resources);
                            }
                        }

                    }
                }
                foreach ($resource_mapping['resources'][$type][$subtype]['path'] as $nature => $path_key_bundlegroup) {

                    foreach ($path_key_bundlegroup as $path => $key_bundlegroups) {

                        foreach ($key_bundlegroups as $keyId => $bundleGroupIds) {

                            // When generating the bundle(group)s, the key is the cache name
                            $model_instance_id = array_search($keyId, $resource_mapping['keys']);

                            foreach ($bundleGroupIds as $bundleGroupId) {

                                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                    $pop_resourceloader_generatedfilesmanager->setJsBundlegroupIds($model_instance_id, $subtype, array($bundleGroupId));
                                }
                                elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                    $pop_resourceloader_generatedfilesmanager->setCssBundlegroupIds($model_instance_id, $subtype, array($bundleGroupId));
                                }

                                $bundle_ids = $resource_mapping['bundle-groups'][$type][$subtype][$bundleGroupId];
                                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                    $pop_resourceloader_generatedfilesmanager->setJsBundleIds($model_instance_id, $subtype, $bundle_ids);
                                }
                                elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                    $pop_resourceloader_generatedfilesmanager->setCssBundleIds($model_instance_id, $subtype, $bundle_ids);
                                }

                                // Save the resources
                                $resources = array();
                                foreach ($bundle_ids as $bundleId) {

                                    $resources = array_unique(
                                        array_merge(
                                            $resources,
                                            $resource_mapping['bundles'][$type][$subtype][$bundleId]
                                        ),
                                        SORT_REGULAR
                                    );
                                }
                                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                                    $pop_resourceloader_generatedfilesmanager->setJsResources($model_instance_id, $subtype, $resources);
                                }
                                if ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                                    $pop_resourceloader_generatedfilesmanager->setCssResources($model_instance_id, $subtype, $resources);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
    
/**
 * Initialize
 */
global $pop_resourceloader_storagegenerator;
$pop_resourceloader_storagegenerator = new PoP_ResourceLoader_StorageGenerator();
