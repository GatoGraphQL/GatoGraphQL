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

    protected static function get_resourcemapping_options($options) {

        // Extract the options which can make the resource-mapping return a different result, to use it for caching.
        // Eg: option 'match-paths', 'match-hierarchy' and 'match-format' do not alter it, so they are not needed to cache the results
        return array_intersect_key(
            $options,
            array(
                'ignore-resources' => '',
                'from-current-vars' => '',
            )
        );
    }

    public static function get_resources($fetching_json, $options = array()) {

        $key = json_encode(self::get_resourcemapping_options($options)).($fetching_json ? 'fetching-json' : 'loading-frame');
        if (!is_null(self::$resources[$key])) {
            return self::$resources[$key];
        }

        $flat_resources = array(
            'home' => array(),
            'author' => array(),
            'tag' => array(),
            '404' => array(),
        );
        $path_resources = array(
            'single' => array(),
            'page' => array(),
        );

        // Extract the resources from the current vars: needed when doing generate_bundlefile_on_runtime()
        if ($options['from-current-vars']) {

            $engine = PoP_Engine_Factory::get_instance();
            $toplevel_template_id = $engine->get_toplevel_template_id();
            // global $gd_template_processor_runtimecache;
            // $gd_template_processor_runtimecache->setUseVarsIdentifier(true);
            // Re-use the already-generated $atts
            $options['use-engine-toplevel-atts'] = true;
            $resources = PoP_ResourceLoaderProcessorUtils::get_resources_from_current_vars($fetching_json, $toplevel_template_id, $options);
            // $gd_template_processor_runtimecache->setUseVarsIdentifier(false);

            $hierarchies = array(
                GD_TEMPLATE_TOPLEVEL_HOME => 'home',
                GD_TEMPLATE_TOPLEVEL_TAG => 'tag',
                GD_TEMPLATE_TOPLEVEL_AUTHOR => 'author',
                GD_TEMPLATE_TOPLEVEL_404 => '404',
                GD_TEMPLATE_TOPLEVEL_PAGE => 'page',
                GD_TEMPLATE_TOPLEVEL_SINGLE => 'single',
            );
            $hierarchy = $hierarchies[$toplevel_template_id];

            global $gd_template_varshashprocessor_manager;
            $cacheprocessor = $gd_template_varshashprocessor_manager->get_processor($toplevel_template_id);
            $key = $cacheprocessor->get_vars_hash_id($toplevel_template_id);
            
            // Assign it under the appropriate level in the object
            switch ($toplevel_template_id) {

                case GD_TEMPLATE_TOPLEVEL_HOME:
                case GD_TEMPLATE_TOPLEVEL_TAG:
                case GD_TEMPLATE_TOPLEVEL_AUTHOR:
                case GD_TEMPLATE_TOPLEVEL_404:

                    $flat_resources[$hierarchy][$key] = $resources;
                    break;

                case GD_TEMPLATE_TOPLEVEL_PAGE:
                case GD_TEMPLATE_TOPLEVEL_SINGLE:

                    $vars = GD_TemplateManager_Utils::get_vars();
                    $path = trailingslashit(GD_TemplateManager_Utils::get_post_path($vars['global-state']['post']->ID, true));
                    $path_resources[$hierarchy][$path][$key] = $resources;
                    break;
            }
        }
        else {

            // Get all the resources, for the different hierarchies
            $flat_resources = array(
                'home' => apply_filters(
                    'PoP_ResourceLoader_FileReproduction_Utils:resources:home',
                    array(),
                    $fetching_json,
                    $options
                ),
                'author' => apply_filters(
                    'PoP_ResourceLoader_FileReproduction_Utils:resources:author',
                    array(),
                    $fetching_json,
                    $options
                ),
                'tag' => apply_filters(
                    'PoP_ResourceLoader_FileReproduction_Utils:resources:tag',
                    array(),
                    $fetching_json,
                    $options
                ),
            );
            // 404: it only makes sense to compute it for "loading-frame". 
            // For "fetching-json", it will never be linked to
            if (!$fetching_json) {
                
                $flat_resources['404'] = apply_filters(
                    'PoP_ResourceLoader_FileReproduction_Utils:resources:404',
                    array(),
                    $fetching_json,
                    $options
                );
            }
            $path_resources = array(
                'single' => apply_filters(
                    'PoP_ResourceLoader_FileReproduction_Utils:resources:single',
                    array(),
                    $fetching_json,
                    $options
                ),
                'page' => apply_filters(
                    'PoP_ResourceLoader_FileReproduction_Utils:resources:page',
                    array(),
                    $fetching_json,
                    $options
                ),
            );
        }

        // "fetching-json": Remove those resources which are already loaded in "loading-frame"
        // This will make resources.js much smaller
        if ($ignore_resources = $options['ignore-resources']) {

            foreach ($flat_resources as $hierarchy => $key_resources) {
                foreach ($key_resources as $key => $resources) {
                    $flat_resources[$hierarchy][$key] = array_values(array_diff(
                        $resources,
                        $ignore_resources
                    ));
                }
            }
            foreach ($path_resources as $hierarchy => $path_key_resources) {
                foreach ($path_key_resources as $path => $key_resources) {
                    foreach ($key_resources as $key => $resources) {
                        $path_resources[$hierarchy][$path][$key] = array_values(array_diff(
                            $resources,
                            $ignore_resources
                        ));
                    }
                }
            }
            // $flat_resources['home'] = array_values(array_diff(
            //     $flat_resources['home'],
            //     $ignore_resources
            // ));
            // $flat_resources['author'] = array_values(array_diff(
            //     $flat_resources['author'],
            //     $ignore_resources
            // ));
            // $flat_resources['tag'] = array_values(array_diff(
            //     $flat_resources['tag'],
            //     $ignore_resources
            // ));
            // foreach ($path_resources['single'] as $path => $resources) {
            //     $path_resources['single'][$path] = array_values(array_diff(
            //         $resources,
            //         $ignore_resources
            //     ));
            // }
            // foreach ($path_resources['page'] as $path => $resources) {
            //     $path_resources['page'][$path] = array_values(array_diff(
            //         $resources,
            //         $ignore_resources
            //     ));
            // }
        }

        self::$resources[$key] = array(
            'flat' => $flat_resources,
            'path' => $path_resources,
        );

        return self::$resources[$key];
    }

    public static function get_resource_mapping($fetching_json, $options = array()) {

        global $pop_resourceloaderprocessor_manager;

        $encoded = json_encode(self::get_resourcemapping_options($options)).($fetching_json ? 'fetching-json' : 'loading-frame');
        if (!is_null(self::$resource_mapping[$encoded])) {
            return self::$resource_mapping[$encoded];
        }

        $match_paths = $options['match-paths'];
        $match_hierarchy = $options['match-hierarchy'];
        $match_format = $options['match-format'];
        
        // Get all the resources, for the different hierarchies
        $app_resources = self::get_resources($fetching_json, $options);
        $flat_resources = $app_resources['flat'];
        $path_resources = $app_resources['path'];

        // If fetching-json, then no need to add the random bit to the bundle(group) ID
        // This works fine as long as we don't copy bundle-resourceloader-mapping.json, etc files from STAGING to PROD,
        $addRandom = !$fetching_json;

        // Because the items in the hierarchies will be duplicated, we create a normalized DB from all the values,
        // and assign an identifier to the bundle of resources, so the size of the generated file will be smaller
        $normal_js_bundles = $vendor_js_bundles = $dynamic_js_bundles = $template_js_bundles = array();
        $normal_css_bundles = $vendor_css_bundles = $dynamic_css_bundles = array();
        $normal_flat_js_resourcebundles = $normal_path_js_resourcebundles = array();
        $vendor_flat_js_resourcebundles = $vendor_path_js_resourcebundles = array();
        $dynamic_flat_js_resourcebundles = $dynamic_path_js_resourcebundles = array();
        $template_flat_js_resourcebundles = $template_path_js_resourcebundles = array();
        $normal_flat_css_resourcebundles = $normal_path_css_resourcebundles = array();
        $vendor_flat_css_resourcebundles = $vendor_path_css_resourcebundles = array();
        $dynamic_flat_css_resourcebundles = $dynamic_path_css_resourcebundles = array();
        $iterate = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => array(
                    'bundles' => &$normal_js_bundles,
                    'resourcebundles' => &$normal_flat_js_resourcebundles,
                ),
                POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => array(
                    'bundles' => &$vendor_js_bundles,
                    'resourcebundles' => &$vendor_flat_js_resourcebundles,
                ),
                POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => array(
                    'bundles' => &$dynamic_js_bundles,
                    'resourcebundles' => &$dynamic_flat_js_resourcebundles,
                ),
                POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE => array(
                    'bundles' => &$template_js_bundles,
                    'resourcebundles' => &$template_flat_js_resourcebundles,
                ),
            ),
            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => array(
                    'bundles' => &$normal_css_bundles,
                    'resourcebundles' => &$normal_flat_css_resourcebundles,
                ),
                POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => array(
                    'bundles' => &$vendor_css_bundles,
                    'resourcebundles' => &$vendor_flat_css_resourcebundles,
                ),
                POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => array(
                    'bundles' => &$dynamic_css_bundles,
                    'resourcebundles' => &$dynamic_flat_css_resourcebundles,
                ),
            ),
        );
        foreach ($flat_resources as $hierarchy => $key_resources) {

            // Check that it satisfies the $match_hierarchy to filter elements (eg: all 'home' ones)
            // If it does not, skip this item
            if ($match_hierarchy && $match_hierarchy != $hierarchy) {
                continue;
            }

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
                $vendor_js_resources = $pop_resourceloaderprocessor_manager->filter_vendor($js_resources);
                $dynamic_js_resources = $pop_resourceloaderprocessor_manager->filter_dynamic($js_resources);
                $template_js_resources = $pop_resourceloaderprocessor_manager->filter_template($js_resources);
                $normal_js_resources = array_diff(
                    $js_resources,
                    $vendor_js_resources,
                    $dynamic_js_resources,
                    $template_js_resources
                );
                $css_resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
                $vendor_css_resources = $pop_resourceloaderprocessor_manager->filter_vendor($css_resources);
                $dynamic_css_resources = $pop_resourceloaderprocessor_manager->filter_dynamic($css_resources);
                $normal_css_resources = array_diff(
                    $css_resources,
                    $vendor_css_resources,
                    $dynamic_css_resources
                );
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resources'] = &$normal_js_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resources'] = &$vendor_js_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resources'] = &$dynamic_js_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resources'] = &$template_js_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resources'] = &$normal_css_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resources'] = &$vendor_css_resources;
                $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resources'] = &$dynamic_css_resources;
                foreach ($iterate as $type => &$subtype_iterate_item) {
                    foreach ($subtype_iterate_item as $subtype => &$iterate_item) {

                        $iterate_item['resourcebundles'][$hierarchy][$key] = array();
                        $resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources($iterate_item['resources']);
                        foreach ($resources_set as $resources_item) {

                            // Remove the indexes, sometimes added from I don't know where
                            $resources_item = array_unique(array_values($resources_item));

                            // // Skip if empty
                            // if (!$resources_item) {
                            //     continue;
                            // }
                            
                            // Calculate the hash from that bundle of resources
                            $bundleId = PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources_item, $addRandom);

                            // Add it to the bundles DB
                            $iterate_item['bundles'][$bundleId] = $resources_item;

                            // Modify the resources list, to store the bundle id instead of the list of resources
                            $iterate_item['resourcebundles'][$hierarchy][$key][] = $bundleId;
                        }
                    }
                }
            }
        }
        // Repeated code. This is ugly, but well, it works
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundles'] = &$normal_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundles'] = &$vendor_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundles'] = &$dynamic_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resourcebundles'] = &$template_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundles'] = &$normal_path_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundles'] = &$vendor_path_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundles'] = &$dynamic_path_css_resourcebundles;
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
                    $vendor_js_resources = $pop_resourceloaderprocessor_manager->filter_vendor($js_resources);
                    $dynamic_js_resources = $pop_resourceloaderprocessor_manager->filter_dynamic($js_resources);
                    $template_js_resources = $pop_resourceloaderprocessor_manager->filter_template($js_resources);
                    $normal_js_resources = array_diff(
                        $js_resources,
                        $vendor_js_resources,
                        $dynamic_js_resources,
                        $template_js_resources
                    );
                    $css_resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
                    $vendor_css_resources = $pop_resourceloaderprocessor_manager->filter_vendor($css_resources);
                    $dynamic_css_resources = $pop_resourceloaderprocessor_manager->filter_dynamic($css_resources);
                    $normal_css_resources = array_diff(
                        $css_resources,
                        $vendor_css_resources,
                        $dynamic_css_resources
                    );
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resources'] = &$normal_js_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resources'] = &$vendor_js_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resources'] = &$dynamic_js_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resources'] = &$template_js_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resources'] = &$normal_css_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resources'] = &$vendor_css_resources;
                    $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resources'] = &$dynamic_css_resources;
                    foreach ($iterate as $type => &$subtype_iterate_item) {
                        foreach ($subtype_iterate_item as $subtype => &$iterate_item) {
                            $resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources($iterate_item['resources']);
                            $iterate_item['resourcebundles'][$hierarchy][$path][$key] = array();
                            foreach ($resources_set as $resources_item) {
                                $resources_item = array_unique(array_values($resources_item));
                                // if (!$resources_item) {
                                //     continue;
                                // }
                                $bundleId = PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources_item, $addRandom);
                                $iterate_item['bundles'][$bundleId] = $resources_item;
                                $iterate_item['resourcebundles'][$hierarchy][$path][$key][] = $bundleId;
                            }
                        }
                    }
                }
            }
        }

        // Replace the bundles with bundleGroups, to further reduce the size of the output file
        // Also replace the key with a $key_id, to compress even further
        $keys = array();
        $normal_js_bundle_groups = $vendor_js_bundle_groups = $dynamic_js_bundle_groups = $template_js_bundle_groups = array();
        $normal_css_bundle_groups = $vendor_css_bundle_groups = $dynamic_css_bundle_groups = array();
        $normal_flat_js_resourcebundlegroups = $vendor_flat_js_resourcebundlegroups = $dynamic_flat_js_resourcebundlegroups = $template_flat_js_resourcebundlegroups = array();
        $normal_path_js_resourcebundlegroups = $vendor_path_js_resourcebundlegroups = $dynamic_path_js_resourcebundlegroups = $template_path_js_resourcebundlegroups = array();
        $normal_flat_css_resourcebundlegroups = $vendor_flat_css_resourcebundlegroups = $dynamic_flat_css_resourcebundlegroups = array();
        $normal_path_css_resourcebundlegroups = $vendor_path_css_resourcebundlegroups = $dynamic_path_css_resourcebundlegroups = array();
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['bundlegroups'] = &$normal_js_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['bundlegroups'] = &$vendor_js_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['bundlegroups'] = &$dynamic_js_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['bundlegroups'] = &$template_js_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['bundlegroups'] = &$normal_css_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['bundlegroups'] = &$vendor_css_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['bundlegroups'] = &$dynamic_css_bundle_groups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundles'] = &$normal_flat_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundles'] = &$vendor_flat_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundles'] = &$dynamic_flat_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resourcebundles'] = &$template_flat_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundles'] = &$normal_flat_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundles'] = &$vendor_flat_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundles'] = &$dynamic_flat_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundlegroups'] = &$normal_flat_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundlegroups'] = &$vendor_flat_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundlegroups'] = &$dynamic_flat_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resourcebundlegroups'] = &$template_flat_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundlegroups'] = &$normal_flat_css_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundlegroups'] = &$vendor_flat_css_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundlegroups'] = &$dynamic_flat_css_resourcebundlegroups;
        foreach ($iterate as $type => &$subtype_iterate_item) {
            foreach ($subtype_iterate_item as $subtype => &$iterate_item) {
                foreach ($iterate_item['resourcebundles'] as $hierarchy => $key_bundles) {

                    $iterate_item['resourcebundlegroups'][$hierarchy] = $iterate_item['resourcebundlegroups'][$hierarchy] ?? array();
                    foreach ($key_bundles as $key => $resourcebundles) {

                        // // Skip if empty
                        // if (!$resourcebundles) {
                        //     continue;
                        // }
                        
                        $bundleGroupId = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($resourcebundles, $addRandom);
                        $iterate_item['bundlegroups'][$bundleGroupId] = $resourcebundles;
                        $keyId = PoP_ResourceLoaderProcessorUtils::get_key_id($key);
                        $keys[$key] = $keyId;
                        $iterate_item['resourcebundlegroups'][$hierarchy][$keyId] = array($bundleGroupId);
                    }
                }
            }
        }
        // Repeated code. This is ugly, but well, it works
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundles'] = &$normal_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundles'] = &$vendor_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundles'] = &$dynamic_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resourcebundles'] = &$template_path_js_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundles'] = &$normal_path_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundles'] = &$vendor_path_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundles'] = &$dynamic_path_css_resourcebundles;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundlegroups'] = &$normal_path_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundlegroups'] = &$vendor_path_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundlegroups'] = &$dynamic_path_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_JS][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE]['resourcebundlegroups'] = &$template_path_js_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]['resourcebundlegroups'] = &$normal_path_css_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]['resourcebundlegroups'] = &$vendor_path_css_resourcebundlegroups;
        $iterate[POP_RESOURCELOADER_RESOURCETYPE_CSS][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]['resourcebundlegroups'] = &$dynamic_path_css_resourcebundlegroups;
        foreach ($iterate as $type => &$subtype_iterate_item) {
            foreach ($subtype_iterate_item as $subtype => &$iterate_item) {
                foreach ($iterate_item['resourcebundles'] as $hierarchy => $path_key_bundles) {
                    foreach ($path_key_bundles as $path => $key_bundles) {
                        if ($match_paths && !in_array($path, $match_paths)) {
                            continue;
                        }
                        $iterate_item['resourcebundlegroups'][$hierarchy][$path] = $iterate_item['resourcebundlegroups'][$hierarchy][$path] ?? array();
                        foreach ($key_bundles as $key => $resourcebundles) {
                            // if (!$resourcebundles) {
                            //     continue;
                            // }
                            $bundleGroupId = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($resourcebundles, $addRandom);
                            $iterate_item['bundlegroups'][$bundleGroupId] = $resourcebundles;
                            $keyId = PoP_ResourceLoaderProcessorUtils::get_key_id($key);
                            $keys[$key] = $keyId;
                            $iterate_item['resourcebundlegroups'][$hierarchy][$path][$keyId] = array($bundleGroupId);
                        }
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
        $all_js_resources = array_merge(
            array_unique(array_flatten(array_values($normal_js_bundles))),
            array_unique(array_flatten(array_values($vendor_js_bundles))),
            array_unique(array_flatten(array_values($dynamic_js_bundles))),
            array_unique(array_flatten(array_values($template_js_bundles)))
        );
        $all_css_resources = array_merge(
            array_unique(array_flatten(array_values($normal_css_bundles))),
            array_unique(array_flatten(array_values($vendor_css_bundles))),
            array_unique(array_flatten(array_values($dynamic_css_bundles)))
        );
        $all_resources = array_merge(
            $all_js_resources,
            $all_css_resources
        );
        foreach ($all_resources as $resource) {

            // Add the version also
            $sources[$resource] = $pop_resourceloaderprocessor_manager->get_file_url($resource, true);
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
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => array(
                        'flat' => $normal_flat_js_resourcebundlegroups ?? array(),
                        'path' => $normal_path_js_resourcebundlegroups ?? array(),
                    ),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => array(
                        'flat' => $vendor_flat_js_resourcebundlegroups ?? array(),
                        'path' => $vendor_path_js_resourcebundlegroups ?? array(),
                    ),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => array(
                        'flat' => $dynamic_flat_js_resourcebundlegroups ?? array(),
                        'path' => $dynamic_path_js_resourcebundlegroups ?? array(),
                    ),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE => array(
                        'flat' => $template_flat_js_resourcebundlegroups ?? array(),
                        'path' => $template_path_js_resourcebundlegroups ?? array(),
                    ),
                ),
                POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => array(
                        'flat' => $normal_flat_css_resourcebundlegroups ?? array(),
                        'path' => $normal_path_css_resourcebundlegroups ?? array(),
                    ),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => array(
                        'flat' => $vendor_flat_css_resourcebundlegroups ?? array(),
                        'path' => $vendor_path_css_resourcebundlegroups ?? array(),
                    ),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => array(
                        'flat' => $dynamic_flat_css_resourcebundlegroups ?? array(),
                        'path' => $dynamic_path_css_resourcebundlegroups ?? array(),
                    ),
                ),
            ),
            'bundles' => array(
                POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_js_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_js_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_js_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE => $template_js_bundles ?? array(),
                ),
                POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_css_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_css_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_css_bundles ?? array(),
                ),
            ),
            'bundle-groups' => array(
                POP_RESOURCELOADER_RESOURCETYPE_JS => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_js_bundle_groups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_js_bundle_groups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_js_bundle_groups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE => $template_js_bundle_groups ?? array(),
                ),
                POP_RESOURCELOADER_RESOURCETYPE_CSS => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_css_bundle_groups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_css_bundle_groups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_css_bundle_groups ?? array(),
                ),
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