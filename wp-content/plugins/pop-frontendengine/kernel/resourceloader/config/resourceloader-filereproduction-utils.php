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
        $flat_resources = array(
            'home' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:resources:home',
                array(),
                $fetching_json
            ),
            'author' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:resources:author',
                array(),
                $fetching_json
            ),
            'tag' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:resources:tag',
                array(),
                $fetching_json
            ),
        );
        // 404: it only makes sense to compute it for "loading-frame". 
        // For "fetching-json", it will never be linked to
        if (!$fetching_json) {
            
            $flat_resources['404'] = apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:resources:404',
                array(),
                $fetching_json
            );
        }
        $path_resources = array(
            'single' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:resources:single',
                array(),
                $fetching_json
            ),
            'page' => apply_filters(
                'PoP_ResourceLoader_FileReproduction_Config:resources:page',
                array(),
                $fetching_json
            ),
        );

        self::$resources[$key] = array(
            'flat' => $flat_resources,
            'path' => $path_resources,
        );

        return self::$resources[$key];
    }

    public static function get_resource_mapping($fetching_json, $options = array()) {

        global $pop_resourceloaderprocessor_manager;

        $encoded = json_encode($options).($fetching_json ? 'fetching-json' : 'loading-frame');
        if (!is_null(self::$resource_mapping[$encoded])) {
            return self::$resource_mapping[$encoded];
        }

        $match_paths = $options['match-paths'];
        $match_hierarchy = $options['match-hierarchy'];
        $match_format = $options['match-format'];
        
        // Get all the resources, for the different hierarchies
        $app_resources = self::get_resources($fetching_json);
        $flat_resources = $app_resources['flat'];
        $path_resources = $app_resources['path'];

        // Because the items in the hierarchies will be duplicated, we create a normalized DB from all the values,
        // and assign an identifier to the bundle of resources, so the size of the generated file will be smaller
        $js_bundles = $css_bundles = array();
        $flat_js_resourcebundles = $path_js_resourcebundles = $flat_css_resourcebundles = $path_css_resourcebundles = array();
        $iterate = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                'bundles' => &$js_bundles,
                'resourcebundles' => &$flat_js_resourcebundles,
            ),
            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                'bundles' => &$css_bundles,
                'resourcebundles' => &$flat_css_resourcebundles,
            ),
        );
        foreach ($flat_resources as $hierarchy => $key_resources) {

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

                // Resources includes both .js and .css. Split them into the 2 cases
                $js_resources = $pop_resourceloaderprocessor_manager->filter_js($resources);
                $css_resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resources'] = &$js_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resources'] = &$css_resources;
                foreach ($iterate as $type => &$iterate_item) {

                    $iterate_item['resourcebundles'][$hierarchy][$key] = array();
                    $resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources($iterate_item['resources']);
                    foreach ($resources_set as $resources_item) {

                        // Remove the indexes, sometimes added from I don't know where
                        $resources_item = array_unique(array_values($resources_item));
                        
                        // Calculate the hash from that bundle of resources
                        $bundleId = PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources_item);

                        // Add it to the bundles DB
                        $iterate_item['bundles'][$bundleId] = $resources_item;

                        // Modify the resources list, to store the bundle id instead of the list of resources
                        $iterate_item['resourcebundles'][$hierarchy][$key][] = $bundleId;
                    }
                }
            }
        }
        // Repeated code. This is ugly, but well, it works
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resourcebundles'] = &$path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resourcebundles'] = &$path_css_resourcebundles;
        foreach ($path_resources as $hierarchy => $path_key_resources) {
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
                    $js_resources = $pop_resourceloaderprocessor_manager->filter_js($resources);
                    $css_resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resources'] = &$js_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resources'] = &$css_resources;
                    foreach ($iterate as $type => &$iterate_item) {
                        $resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources($iterate_item['resources']);
                        $iterate_item['resourcebundles'][$hierarchy][$path][$key] = array();
                        foreach ($resources_set as $resources_item) {
                            $resources_item = array_unique(array_values($resources_item));
                            $bundleId = PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources_item);
                            $iterate_item['bundles'][$bundleId] = $resources_item;
                            $iterate_item['resourcebundles'][$hierarchy][$path][$key][] = $bundleId;
                        }
                    }
                }
            }
        }

        // Replace the bundles with bundleGroups, to further reduce the size of the output file
        // Also replace the key with a $key_id, to compress even further
        $keys = $js_bundle_groups = $css_bundle_groups = array();
        $flat_js_resourcebundlegroups = $path_js_resourcebundlegroups = $flat_css_resourcebundlegroups = $path_css_resourcebundlegroups = array();
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['bundlegroups'] = &$js_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['bundlegroups'] = &$css_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resourcebundles'] = &$flat_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resourcebundles'] = &$flat_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resourcebundlegroups'] = &$flat_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resourcebundlegroups'] = &$flat_css_resourcebundlegroups;
        foreach ($iterate as $type => &$iterate_item) {
            foreach ($iterate_item['resourcebundles'] as $hierarchy => $key_bundles) {

                $iterate_item['resourcebundlegroups'][$hierarchy] = $iterate_item['resourcebundlegroups'][$hierarchy] ?? array();
                foreach ($key_bundles as $key => $resourcebundles) {
                    
                    $bundleGroupId = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($resourcebundles);
                    $iterate_item['bundlegroups'][$bundleGroupId] = $resourcebundles;
                    $keyId = PoP_ResourceLoaderProcessorUtils::get_key_id($key);
                    $keys[$key] = $keyId;
                    $iterate_item['resourcebundlegroups'][$hierarchy][$keyId] = array($bundleGroupId);
                }
            }
        }
        // Repeated code. This is ugly, but well, it works
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resourcebundles'] = &$path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resourcebundles'] = &$path_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS]['resourcebundlegroups'] = &$path_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS]['resourcebundlegroups'] = &$path_css_resourcebundlegroups;
        foreach ($iterate as $type => &$iterate_item) {
            foreach ($iterate_item['resourcebundles'] as $hierarchy => $path_key_bundles) {
                foreach ($path_key_bundles as $path => $key_bundles) {
                    if ($match_paths && !in_array($path, $match_paths)) {
                        continue;
                    }
                    $iterate_item['resourcebundlegroups'][$hierarchy][$path] = $iterate_item['resourcebundlegroups'][$hierarchy][$path] ?? array();
                    foreach ($key_bundles as $key => $resourcebundles) {
                        
                        $bundleGroupId = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($resourcebundles);
                        $iterate_item['bundlegroups'][$bundleGroupId] = $resourcebundles;
                        $keyId = PoP_ResourceLoaderProcessorUtils::get_key_id($key);
                        $keys[$key] = $keyId;
                        $iterate_item['resourcebundlegroups'][$hierarchy][$path][$keyId] = array($bundleGroupId);
                    }
                }
            }
        }

        // Map all the resources to their filenames, and set them as 'defer' if they need to be executed in order
        global $pop_jsresourceloaderprocessor_manager;
        $sources = $orderedload_resources = array();
        $types = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
        );
        $all_js_resources = array_unique(array_flatten(array_values($js_bundles)));
        $all_css_resources = array_unique(array_flatten(array_values($css_bundles)));
        $all_resources = array_merge(
            $all_js_resources,
            $all_css_resources
        );
        foreach ($all_resources as $resource) {

            // Add the version also
            $sources[$resource] = $pop_resourceloaderprocessor_manager->get_file_url($resource, true);
            // $types[$resource] = $pop_resourceloaderprocessor_manager->get_type($resource);
            $resource_type = $pop_resourceloaderprocessor_manager->get_type($resource);
            $types[$resource_type][] = $resource;
        }
        // The code below applies only for JS resources
        foreach ($all_js_resources as $js_resource) {

            if ($pop_jsresourceloaderprocessor_manager->async_load_in_order($js_resource)) {

                $orderedload_resources[] = $js_resource;
            }
        }

        // Save all the information in the return variable
        self::$resource_mapping[$encoded] = array(
            'resources' => array(
                POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                    'flat' => $flat_js_resourcebundlegroups,
                    'path' => $path_js_resourcebundlegroups,
                ),
                POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                    'flat' => $flat_css_resourcebundlegroups,
                    'path' => $path_css_resourcebundlegroups,
                ),
            ),
            'bundles' => array(
                POP_RESOURCELOADER_RESOURCETYPE_JS => $js_bundles,
                POP_RESOURCELOADER_RESOURCETYPE_CSS => $css_bundles,
            ),
            'bundle-groups' => array(
                POP_RESOURCELOADER_RESOURCETYPE_JS => $js_bundle_groups,
                POP_RESOURCELOADER_RESOURCETYPE_CSS => $css_bundle_groups,
            ),
            'keys' => $keys,
            'sources' => $sources,
            'types' => $types,
            'ordered-load-resources' => $orderedload_resources,
        );

        return self::$resource_mapping[$encoded];
    }
}
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
PoP_ResourceLoader_FileReproduction_Utils::init();