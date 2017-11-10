<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADERIDENTIFIER_FORMAT', 'f:');
define ('POP_RESOURCELOADERIDENTIFIER_TAB', 't:');
define ('POP_RESOURCELOADERIDENTIFIER_TARGET', 'r:');

class PoP_ResourceLoader_FileReproduction_Utils {

    protected static $resource_mapping, $resources;

    public static function init() {

        self::$resource_mapping = array();
        self::$resources = array();
    }

    public static function get_resources($fetching_json) {

        $key = $fetching_json ? 'fetching-json' : 'loading-frame';
        if (!is_null(self::$resources[$key])) {
            return self::$resources[$key];
        }

        // Get all the resources, for the different hierarchies
        $flat_js_resources = array(
            'home' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:js-resources:home',
                array(),
                $fetching_json
            ),
            'author' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:js-resources:author',
                array(),
                $fetching_json
            ),
            'tag' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:js-resources:tag',
                array(),
                $fetching_json
            )
        );
        $path_js_resources = array(
            'single' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:js-resources:single',
                array(),
                $fetching_json
            ),
            'page' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:js-resources:page',
                array(),
                $fetching_json
            ),
        );

        self::$resources[$key] = array(
            'js' => array(
                'flat' => $flat_js_resources,
                'path' => $path_js_resources,
            ),
        );

        return self::$resources[$key];
    }

    public static function get_resource_mapping($fetching_json, $options = array()) {

        $encoded = json_encode($options).($fetching_json ? 'fetching-json' : 'loading-frame');
        if (!is_null(self::$resource_mapping[$encoded])) {
            return self::$resource_mapping[$encoded];
        }

        $match_paths = $options['match-paths'];
        $match_hierarchy = $options['match-hierarchy'];
        $match_format = $options['match-format'];
        
        // Get all the resources, for the different hierarchies
        $app_resources = self::get_resources($fetching_json);
        $flat_js_resources = $app_resources['js']['flat'];
        $path_js_resources = $app_resources['js']['path'];

        // Because the items in the hierarchies will be duplicated, we create a normalized DB from all the values,
        // and assign an identifier to the bundle of resources, so the size of the generated file will be smaller
        $bundles = array();
        $flat_js_resourcebundles = $path_js_resourcebundles = array();
        foreach ($flat_js_resources as $hierarchy => $key_resources) {

            // Check that it satisfies the $match_hierarchy to filter elements (eg: all 'home' ones)
            // If it does not, skip this item
            if ($match_hierarchy && $match_hierarchy != $hierarchy) {
                continue;
            }

            // foreach ($key_resources as $key => $resources_set) {
            foreach ($key_resources as $key => $resources) {

                // If there is nothing, then do nothing
                if (!$resources) {
                    continue;
                }

                // Check that it satisfies the $match_format to filter elements (eg: all 'default' ones)
                // If it does not, skip this item
                if ($match_format) {
                    
                    // Obtain the format from the $key
                    $key_parts = explode(GD_SEPARATOR_RESOURCELOADER, $key);
                    $format = substr(explode(GD_SEPARATOR_RESOURCELOADER, $key)[0], strlen(POP_RESOURCELOADERIDENTIFIER_FORMAT));
                    if ($match_format != $format) {
                        continue;
                    }
                }

                $resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources(array($resources));

                $flat_js_resourcebundles[$hierarchy][$key] = array();
                foreach ($resources_set as $resources_item) {

                    // Remove the indexes, sometimes added from I don't know where
                    $resources_item = array_unique(array_values($resources_item));
                    
                    // Calculate the hash from that bundle of resources
                    $bundleId = PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources_item);

                    // Add it to the bundles DB
                    $bundles[$bundleId] = $resources_item;

                    // Modify the resources list, to store the bundle id instead of the list of resources
                    $flat_js_resourcebundles[$hierarchy][$key][] = $bundleId;
                }
            }
        }
        // Repeated code. This is ugly, but well, it works
        foreach ($path_js_resources as $hierarchy => $path_key_resources) {
            if ($match_hierarchy && $match_hierarchy != $hierarchy) {
                continue;
            }
            foreach ($path_key_resources as $path => $key_resources) {

                // Check that it satisfies the $match_paths to filter elements
                // If it does not, skip this item
                if ($match_paths && !in_array($path, $match_paths)) {
                    continue;
                }
                // foreach ($key_resources as $key => $resources_set) {
                foreach ($key_resources as $key => $resources) {
                    if (!$resources) {
                        continue;
                    }
                    if ($match_format) {
                        $key_parts = explode(GD_SEPARATOR_RESOURCELOADER, $key);
                        $format = substr(explode(GD_SEPARATOR_RESOURCELOADER, $key)[0], strlen(POP_RESOURCELOADERIDENTIFIER_FORMAT));
                        if ($match_format != $format) {
                            continue;
                        }
                    }
                    $resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources(array($resources));
                    $path_js_resourcebundles[$hierarchy][$path][$key] = array();
                    foreach ($resources_set as $resources_item) {
                        $resources_item = array_unique(array_values($resources_item));
                        $bundleId = PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources_item);
                        $bundles[$bundleId] = $resources_item;
                        $path_js_resourcebundles[$hierarchy][$path][$key][] = $bundleId;
                    }
                }
            }
        }

        // Replace the bundles with bundleGroups, to further reduce the size of the output file
        // Also replace the key with a $key_id, to compress even further
        $bundle_groups = $keys = array();
        $flat_js_resourcebundlegroups = $path_js_resourcebundlegroups = array();
        foreach ($flat_js_resourcebundles as $hierarchy => $key_bundles) {

            $flat_js_resourcebundlegroups[$hierarchy] = $flat_js_resourcebundlegroups[$hierarchy] ?? array();
            foreach ($key_bundles as $key => $resourcebundles) {
                
                $bundleGroupId = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($resourcebundles);
                $bundle_groups[$bundleGroupId] = $resourcebundles;
                $keyId = PoP_ResourceLoaderProcessorUtils::get_key_id($key);
                $keys[$key] = $keyId;
                $flat_js_resourcebundlegroups[$hierarchy][$keyId] = $bundleGroupId;
            }
        }
        foreach ($path_js_resourcebundles as $hierarchy => $path_key_bundles) {
            foreach ($path_key_bundles as $path => $key_bundles) {
                if ($match_paths && !in_array($path, $match_paths)) {
                    continue;
                }
                $path_js_resourcebundlegroups[$hierarchy][$path] = $path_js_resourcebundlegroups[$hierarchy][$path] ?? array();
                foreach ($key_bundles as $key => $resourcebundles) {
                    
                    $bundleGroupId = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($resourcebundles);
                    $bundle_groups[$bundleGroupId] = $resourcebundles;
                    $keyId = PoP_ResourceLoaderProcessorUtils::get_key_id($key);
                    $keys[$key] = $keyId;
                    $path_js_resourcebundlegroups[$hierarchy][$path][$keyId] = $bundleGroupId;
                }
            }
        }

        // Map all the resources to their filenames, and set them as 'defer' if they need to be executed in order
        global $pop_resourceloaderprocessor_manager;

        $sources = $orderedload_resources = array();
        $all_resources = array_unique(array_flatten(array_values($bundles)));
        foreach ($all_resources as $resource) {

            // Add the version also
            $sources[$resource] = $pop_resourceloaderprocessor_manager->get_file_url($resource, true);

            if ($pop_resourceloaderprocessor_manager->async_load_in_order($resource)) {

                $orderedload_resources[] = $resource;
            }
        }

        // Save all the information in the return variable
        self::$resource_mapping[$encoded] = array(
            'bundles' => $bundles,
            'bundle-groups' => $bundle_groups,
            'keys' => $keys,
            'resources' => array(
                'js' => array(
                    'flat' => $flat_js_resourcebundlegroups,
                    'path' => $path_js_resourcebundlegroups,
                ),
            ),
            'sources' => $sources,
            'ordered-load-resources' => $orderedload_resources,
        );

        return self::$resource_mapping[$encoded];
    }
}
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
PoP_ResourceLoader_FileReproduction_Utils::init();