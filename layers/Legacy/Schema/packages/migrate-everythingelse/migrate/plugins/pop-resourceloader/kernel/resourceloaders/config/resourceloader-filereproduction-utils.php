<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

define ('POP_RESOURCELOADERIDENTIFIER_FORMAT', 'f:');
define ('POP_RESOURCELOADERIDENTIFIER_ROUTE', 't:');
define ('POP_RESOURCELOADERIDENTIFIER_TARGET', 'r:');

class PoP_ResourceLoader_FileReproduction_Utils {

    protected static $resource_mapping, $resources;

    public static function init(): void {

        self::$resource_mapping = array();
        self::$resources = array();
    }

    protected static function getResourcemappingOptions($options) {

        // Extract the options which can make the resource-mapping return a different result, to use it for caching.
        // Eg: option 'match-paths', 'match-nature' and 'match-format' do not alter it, so they are not needed to cache the results
        return array_intersect_key(
            $options,
            array(
                'ignore-resources' => '',
                'from-current-vars' => '',
            )
        );
    }

    public static function getResources($modulefilter, $options = array()) {

        $key = json_encode(self::getResourcemappingOptions($options)).$modulefilter;
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
            'generic' => array(),
        );

        // Extract the resources from the current vars: needed when doing generate_bundlefile_on_runtime()
        if ($options['from-current-vars'] ?? null) {
            // Re-use the already-generated $props
            $options['use-engine-entrymodule-props'] = true;
            $resources = PoP_ResourceLoaderProcessorUtils::getResourcesFromCurrentVars($modulefilter, $options);

            $nature = \PoP\Root\App::getState('nature');

            $key = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();

            // Assign it under the appropriate level in the object
            switch ($nature) {

                case UserRequestNature::USER:
                case RequestNature::HOME:
                case TagRequestNature::TAG:
                case RequestNature::NOTFOUND:

                    $flat_resources[$nature][$key] = $resources;
                    break;

                case RequestNature::GENERIC:
                case PageRequestNature::PAGE:
                case CustomPostRequestNature::CUSTOMPOST:

                    $path = GeneralUtils::maybeAddTrailingSlash(\PoPCMSSchema\Posts\Engine_Utils::getCustomPostPath(\PoP\Root\App::getState(['routing', 'queried-object-id']), true));
                    $path_resources[$nature][$path][$key] = $resources;
                    break;
            }
        }
        else {

            // Get all the resources, for the different natures
            $natureresources_manager = PoP_ResourceLoader_NatureResourcesManagerFactory::getInstance();
            $flat_resources = array(
                'home' => $natureresources_manager->getHomeResources($modulefilter, $options),
                'author' => $natureresources_manager->getAuthorResources($modulefilter, $options),
                'tag' => $natureresources_manager->getTagResources($modulefilter, $options),
            );

            // 404: it only makes sense to compute it for "loading-site".
            // For "fetching-json", it will never be linked to
            $loadingSite = PoP_ResourceLoaderProcessorUtils::isLoadingSite($modulefilter);
            if ($loadingSite) {

                $flat_resources['404'] = $natureresources_manager->get404Resources($modulefilter, $options);
            }

            $path_resources = array(
                'single' => $natureresources_manager->getSingleResources($modulefilter, $options),
                'page' => $natureresources_manager->getPageResources($modulefilter, $options),
                'generic' => $natureresources_manager->getGenericNatureResources($modulefilter, $options),
            );
        }

        // "fetching-json": Remove those resources which are already loaded in "loading-site"
        // This will make resources.js much smaller
        if ($ignore_resources = $options['ignore-resources'] ?? null) {
            foreach ($flat_resources as $nature => $key_resources) {
                foreach ($key_resources as $key => $resources) {
                    $flat_resources[$nature][$key] = array_values(array_diff(
                        $resources,
                        $ignore_resources
                    ));
                }
            }
            foreach ($path_resources as $nature => $path_key_resources) {
                foreach ($path_key_resources as $path => $key_resources) {
                    foreach ($key_resources as $key => $resources) {
                        $path_resources[$nature][$path][$key] = array_values(array_diff(
                            $resources,
                            $ignore_resources
                        ));
                    }
                }
            }
        }

        self::$resources[$key] = array(
            'flat' => $flat_resources,
            'path' => $path_resources,
        );

        return self::$resources[$key];
    }

    public static function getResourceMapping($modulefilter, $options = array()) {

        global $pop_resourceloaderprocessor_manager;

        $encoded = json_encode(self::getResourcemappingOptions($options)).$modulefilter;
        if (!is_null(self::$resource_mapping[$encoded])) {
            return self::$resource_mapping[$encoded];
        }

        $matchPaths = $options['match-paths'] ?? null;
        $matchNature = $options['match-nature'] ?? null;
        $matchFormat = $options['match-format'] ?? null;

        // Get all the resources, for the different natures
        $app_resources = self::getResources($modulefilter, $options);
        $flat_resources = $app_resources['flat'];
        $path_resources = $app_resources['path'];

        // If fetching-json, then no need to add the random bit to the bundle(group) ID
        // This works fine as long as we don't copy bundle-resourceloader-mapping.json, etc files from STAGING to PROD,
        $loadingSite = PoP_ResourceLoaderProcessorUtils::isLoadingSite($modulefilter);
        $addRandom = $loadingSite;

        // Because the items in the natures will be duplicated, we create a normalized DB from all the values,
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
        foreach ($flat_resources as $nature => $key_resources) {

            // Check that it satisfies the $matchNature to filter elements (eg: all 'home' ones)
            // If it does not, skip this item
            if ($matchNature && $matchNature != $nature) {
                continue;
            }

            foreach ($key_resources as $key => $resources) {

                // If there is nothing, then do nothing
                if (!$resources) {
                    continue;
                }

                // Check that it satisfies the $matchFormat to filter elements (eg: all 'default' ones)
                // If it does not, skip this item
                if ($matchFormat) {

                    // Obtain the format from the $key
                    $key_parts = explode(GD_SEPARATOR_RESOURCELOADER, $key);
                    $format = substr(explode(GD_SEPARATOR_RESOURCELOADER, $key)[0], strlen(POP_RESOURCELOADERIDENTIFIER_FORMAT));
                    if ($matchFormat != $format) {
                        continue;
                    }
                }

                // Resources includes both .js and .css. Split them into the 2 cases
                $js_resources = $pop_resourceloaderprocessor_manager->filterJs($resources);
                $vendor_js_resources = $pop_resourceloaderprocessor_manager->filterVendor($js_resources);
                $dynamic_js_resources = $pop_resourceloaderprocessor_manager->filterDynamic($js_resources);
                $template_js_resources = $pop_resourceloaderprocessor_manager->filterTemplate($js_resources);
                $normal_js_resources = array_diff(
                    $js_resources,
                    $vendor_js_resources,
                    $dynamic_js_resources,
                    $template_js_resources
                );
                $css_resources = $pop_resourceloaderprocessor_manager->filterCss($resources);
                $vendor_css_resources = $pop_resourceloaderprocessor_manager->filterVendor($css_resources);
                $dynamic_css_resources = $pop_resourceloaderprocessor_manager->filterDynamic($css_resources);
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

                        $iterate_item['resourcebundles'][$nature][$key] = array();
                        $resources_set = PoP_ResourceLoaderProcessorUtils::chunkResources($iterate_item['resources']);
                        foreach ($resources_set as $resources_item) {

                            // Remove the indexes, sometimes added from I don't know where
                            $resources_item = array_unique(
                                array_values($resources_item),
                                SORT_REGULAR
                            );

                            // // Skip if empty
                            // if (!$resources_item) {
                            //     continue;
                            // }

                            // Calculate the hash from that bundle of resources
                            $bundleId = PoP_ResourceLoaderProcessorUtils::getBundleId($resources_item, $addRandom);

                            // Add it to the bundles DB
                            $iterate_item['bundles'][$bundleId] = $resources_item;

                            // Modify the resources list, to store the bundle id instead of the list of resources
                            $iterate_item['resourcebundles'][$nature][$key][] = $bundleId;
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
        foreach ($path_resources as $nature => $path_key_resources) {
            if ($matchNature && $matchNature != $nature) {
                continue;
            }
            foreach ($path_key_resources as $path => $key_resources) {

                // Check that it satisfies the $matchPaths to filter elements
                // If it does not, skip this item
                if ($matchPaths && !in_array($path, $matchPaths)) {
                    continue;
                }
                foreach ($key_resources as $key => $resources) {
                    if (!$resources) {
                        continue;
                    }
                    if ($matchFormat) {
                        $key_parts = explode(GD_SEPARATOR_RESOURCELOADER, $key);
                        $format = substr(explode(GD_SEPARATOR_RESOURCELOADER, $key)[0], strlen(POP_RESOURCELOADERIDENTIFIER_FORMAT));
                        if ($matchFormat != $format) {
                            continue;
                        }
                    }
                    $js_resources = $pop_resourceloaderprocessor_manager->filterJs($resources);
                    $vendor_js_resources = $pop_resourceloaderprocessor_manager->filterVendor($js_resources);
                    $dynamic_js_resources = $pop_resourceloaderprocessor_manager->filterDynamic($js_resources);
                    $template_js_resources = $pop_resourceloaderprocessor_manager->filterTemplate($js_resources);
                    $normal_js_resources = array_diff(
                        $js_resources,
                        $vendor_js_resources,
                        $dynamic_js_resources,
                        $template_js_resources
                    );
                    $css_resources = $pop_resourceloaderprocessor_manager->filterCss($resources);
                    $vendor_css_resources = $pop_resourceloaderprocessor_manager->filterVendor($css_resources);
                    $dynamic_css_resources = $pop_resourceloaderprocessor_manager->filterDynamic($css_resources);
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
                            $resources_set = PoP_ResourceLoaderProcessorUtils::chunkResources($iterate_item['resources']);
                            $iterate_item['resourcebundles'][$nature][$path][$key] = array();
                            foreach ($resources_set as $resources_item) {
                                $resources_item = array_unique(
                                    array_values($resources_item),
                                    SORT_REGULAR
                                );
                                // if (!$resources_item) {
                                //     continue;
                                // }
                                $bundleId = PoP_ResourceLoaderProcessorUtils::getBundleId($resources_item, $addRandom);
                                $iterate_item['bundles'][$bundleId] = $resources_item;
                                $iterate_item['resourcebundles'][$nature][$path][$key][] = $bundleId;
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
                foreach ($iterate_item['resourcebundles'] as $nature => $key_bundles) {

                    $iterate_item['resourcebundlegroups'][$nature] = $iterate_item['resourcebundlegroups'][$nature] ?? array();
                    foreach ($key_bundles as $key => $resourcebundles) {

                        // // Skip if empty
                        // if (!$resourcebundles) {
                        //     continue;
                        // }

                        $bundleGroupId = PoP_ResourceLoaderProcessorUtils::getBundlegroupId($resourcebundles, $addRandom);
                        $iterate_item['bundlegroups'][$bundleGroupId] = $resourcebundles;
                        $keyId = PoP_ResourceLoaderProcessorUtils::getKeyId($key);
                        $keys[$key] = $keyId;
                        $iterate_item['resourcebundlegroups'][$nature][$keyId] = array($bundleGroupId);
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
                foreach ($iterate_item['resourcebundles'] as $nature => $path_key_bundles) {
                    foreach ($path_key_bundles as $path => $key_bundles) {
                        if ($matchPaths && !in_array($path, $matchPaths)) {
                            continue;
                        }
                        $iterate_item['resourcebundlegroups'][$nature][$path] = $iterate_item['resourcebundlegroups'][$nature][$path] ?? array();
                        foreach ($key_bundles as $key => $resourcebundles) {
                            // if (!$resourcebundles) {
                            //     continue;
                            // }
                            $bundleGroupId = PoP_ResourceLoaderProcessorUtils::getBundlegroupId($resourcebundles, $addRandom);
                            $iterate_item['bundlegroups'][$bundleGroupId] = $resourcebundles;
                            $keyId = PoP_ResourceLoaderProcessorUtils::getKeyId($key);
                            $keys[$key] = $keyId;
                            $iterate_item['resourcebundlegroups'][$nature][$path][$keyId] = array($bundleGroupId);
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
        $all_js_resources = self::flattenResources(
            $normal_js_bundles,
            $vendor_js_bundles,
            $dynamic_js_bundles,
            $template_js_bundles
        );
        $all_css_resources = self::flattenResources(
            $normal_css_bundles,
            $vendor_css_bundles,
            $dynamic_css_bundles
        );
        $all_resources = array_merge(
            $all_js_resources,
            $all_css_resources
        );
        foreach ($all_resources as $resource) {

            // Add the version also
            $resourceFullName = ResourceUtils::getResourceFullName($resource);
            $sources[$resourceFullName] = $pop_resourceloaderprocessor_manager->getFileUrl($resource, true);
            $resource_type = $pop_resourceloaderprocessor_manager->getType($resource);
            $types[$resource_type][] = $resourceFullName;
        }
        // The code below applies only for JS resources
        foreach ($all_js_resources as $js_resource) {

            if ($pop_jsresourceloaderprocessor_manager->asyncLoadInOrder($js_resource)) {

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

    private static function flattenResources(array ...$resourceSets): array
    {
        $array = [];
        foreach ($resourceSets as $resourceSet) {
            foreach ($resourceSet as $resources) {
                $array = array_merge(
                    $array,
                    $resources
                );
            }
        }

        return array_unique(
            $array,
            SORT_REGULAR
        );
    }
}
/**
 * Initialize
 */
PoP_ResourceLoader_FileReproduction_Utils::init();
