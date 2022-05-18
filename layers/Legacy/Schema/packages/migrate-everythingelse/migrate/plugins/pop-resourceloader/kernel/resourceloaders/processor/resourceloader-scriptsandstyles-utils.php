<?php

use PoP\ComponentModel\App;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\Facades\Cache\PersistentCacheFacade;
use PoP\ComponentModel\Facades\Cache\TransientCacheManagerFacade;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;

class PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils {

    protected static $calculated_resources = array();
    protected static $dynamic_component_resources;

    public static function getResourcesPack($type, $model_instance_id = null)
    {
        // Check if the list of scripts has been cached in pop-cache/ first
        // If so, just return it from there directly
        global $pop_resourceloader_generatedfilesmanager, $pop_resourceloaderprocessor_manager;
        $cachemanager = null;
        if ($useCache = ComponentModelModuleConfiguration::useComponentModelCache()) {
            $cachemanager = PersistentCacheFacade::getInstance();
        }

        if (!$model_instance_id) {
            $model_instance_id = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
        }

        if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
            $vendor_resources = $pop_resourceloader_generatedfilesmanager->getJsResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
            $normal_resources = $pop_resourceloader_generatedfilesmanager->getJsResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
            $dynamic_resources = $pop_resourceloader_generatedfilesmanager->getJsResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
            $template_resources = $pop_resourceloader_generatedfilesmanager->getJsResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE);
        } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
            $vendor_resources = $pop_resourceloader_generatedfilesmanager->getCssResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
            $normal_resources = $pop_resourceloader_generatedfilesmanager->getCssResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
            $dynamic_resources = $pop_resourceloader_generatedfilesmanager->getCssResources($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
        }
        // Enqueuing order: 1. Vendor, 2. Normal, 3. Dynamic, 4. Template
        // This is because of the dependency order: Dynamic will always depend on normal, which will always depend on vendor
        // Templates may depend on Normal, but never the other way around
        $resources = array_merge(
            $vendor_resources ?? array(),
            $normal_resources ?? array(),
            $dynamic_resources ?? array(),
            $template_resources ?? array()
        );

        // If there were resources in the cached file, then there will also be the corresponding bundles and bundlegroups
        if ($resources) {
            if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                $vendor_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
                $normal_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
                $dynamic_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
                $template_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE);
                $vendor_bundlegroups = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
                $normal_bundlegroups = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
                $dynamic_bundlegroups = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
                $template_bundlegroups = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE);
            } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                $vendor_bundles = $pop_resourceloader_generatedfilesmanager->getCssBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
                $normal_bundles = $pop_resourceloader_generatedfilesmanager->getCssBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
                $dynamic_bundles = $pop_resourceloader_generatedfilesmanager->getCssBundleIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
                $vendor_bundlegroups = $pop_resourceloader_generatedfilesmanager->getCssBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
                $normal_bundlegroups = $pop_resourceloader_generatedfilesmanager->getCssBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
                $dynamic_bundlegroups = $pop_resourceloader_generatedfilesmanager->getCssBundlegroupIds($model_instance_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
            }
            $bundles = array_merge(
                $vendor_bundles ?? array(),
                $normal_bundles ?? array(),
                $dynamic_bundles ?? array(),
                $template_bundles ?? array()
            );
            $bundlegroups = array_merge(
                $vendor_bundlegroups ?? array(),
                $normal_bundlegroups ?? array(),
                $dynamic_bundlegroups ?? array(),
                $template_bundlegroups ?? array()
            );
        } else {
            // If there is no cached one, check if it was generated and cached on runtime
            if ($useCache) {
                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                    $normal_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_NORMAL);
                    $vendor_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_VENDOR);
                    $dynamic_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_DYNAMIC);
                    $template_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_TEMPLATE);
                } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                    $normal_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSRESOURCES_NORMAL);
                    $vendor_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSRESOURCES_VENDOR);
                    $dynamic_resources = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSRESOURCES_DYNAMIC);
                }

                // If the cache does not exists, each of these variables will be `null`, and not an empty array, so the array_merge below would fail
                if ($normal_resources || $vendor_resources || $dynamic_resources || $template_resources) {
                    // Enqueuing order: 1. Vendor, 2. Normal, 3. Dynamic, 4. Template
                    // This is because of the dependency order: Dynamic will always depend on normal, which will always depend on vendor
                    // Templates may depend on Normal, but never the other way around
                    $resources = array_merge(
                        $vendor_resources ?? array(),
                        $normal_resources ?? array(),
                        $dynamic_resources ?? array(),
                        $template_resources ?? array()
                    );
                }
            }

            // If there is cached resources, there will also be bundles and bundlegroups
            if ($resources) {
                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                    $normal_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_NORMAL);
                    $vendor_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_VENDOR);
                    $dynamic_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_DYNAMIC);
                    $template_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_TEMPLATE);

                    $normal_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_NORMAL);
                    $vendor_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_VENDOR);
                    $dynamic_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_DYNAMIC);
                    $template_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_TEMPLATE);
                } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

                    $normal_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLES_NORMAL);
                    $vendor_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLES_VENDOR);
                    $dynamic_bundles = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLES_DYNAMIC);

                    $normal_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLEGROUPS_NORMAL);
                    $vendor_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLEGROUPS_VENDOR);
                    $dynamic_bundlegroups = $cachemanager->getComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLEGROUPS_DYNAMIC);
                }
                $bundles = array_merge(
                    $vendor_bundles ?? array(),
                    $normal_bundles ?? array(),
                    $dynamic_bundles ?? array(),
                    $template_bundles ?? array()
                );
                $bundlegroups = array_merge(
                    $vendor_bundlegroups ?? array(),
                    $normal_bundlegroups ?? array(),
                    $dynamic_bundlegroups ?? array(),
                    $template_bundlegroups ?? array()
                );
            } else {
                // If there is no cached one, generate the resources and cache it
                // Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
                $resources = self::calculateResources($model_instance_id);

                // We have here both .cs and .jss resources. Split them into these, and calculate the bundle(group)s for each
                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                    $resources = $pop_resourceloaderprocessor_manager->filterJs($resources);
                    $template_resources = $pop_resourceloaderprocessor_manager->filterTemplate($resources);
                } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                    $resources = $pop_resourceloaderprocessor_manager->filterCss($resources);
                }

                $vendor_resources = $pop_resourceloaderprocessor_manager->filterVendor($resources);
                $dynamic_resources = $pop_resourceloaderprocessor_manager->filterDynamic($resources);
                $normal_resources = array_diff(
                    $resources,
                    $vendor_resources,
                    $dynamic_resources,
                    $template_resources ?? array()
                );

                // Calculate the bundles and bundlegroups
                $normal_generatedfiles = self::calculateBundles($normal_resources, true);
                $vendor_generatedfiles = self::calculateBundles($vendor_resources, true);
                $dynamic_generatedfiles = self::calculateBundles($dynamic_resources, true);

                $vendor_bundles = $vendor_generatedfiles['bundles'];
                $normal_bundles = $normal_generatedfiles['bundles'];
                $dynamic_bundles = $dynamic_generatedfiles['bundles'];

                $vendor_bundlegroups = $vendor_generatedfiles['bundle-groups'];
                $normal_bundlegroups = $normal_generatedfiles['bundle-groups'];
                $dynamic_bundlegroups = $dynamic_generatedfiles['bundle-groups'];

                if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                    $template_generatedfiles = self::calculateBundles($template_resources, true);
                    $template_bundles = $template_generatedfiles['bundles'];
                    $template_bundlegroups = $template_generatedfiles['bundle-groups'];

                    // Enqueuing order: 1. Vendor, 2. Normal, 3. Dynamic, 4.Templates
                    // This is because of the dependency order: Dynamic will always depend on normal, which will always depend on vendor
                    // Templates may depend on Normal, but never the other way around
                    $bundles = array_merge(
                        $vendor_bundles,
                        $normal_bundles,
                        $dynamic_bundles,
                        $template_bundles
                    );
                    $bundlegroups = array_merge(
                        $vendor_bundlegroups,
                        $normal_bundlegroups,
                        $dynamic_bundlegroups,
                        $template_bundlegroups
                    );
                } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
                    $bundles = array_merge(
                        $vendor_bundles,
                        $normal_bundles,
                        $dynamic_bundles
                    );
                    $bundlegroups = array_merge(
                        $vendor_bundlegroups,
                        $normal_bundlegroups,
                        $dynamic_bundlegroups
                    );
                }

                // Save them in the pop-cache/
                if ($useCache) {
                    if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_NORMAL, $normal_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_VENDOR, $vendor_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_DYNAMIC, $dynamic_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSRESOURCES_TEMPLATE, $template_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_NORMAL, $normal_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_VENDOR, $vendor_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_DYNAMIC, $dynamic_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLES_TEMPLATE, $template_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_NORMAL, $normal_bundlegroups);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_VENDOR, $vendor_bundlegroups);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_DYNAMIC, $dynamic_bundlegroups);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_JSBUNDLEGROUPS_TEMPLATE, $template_bundlegroups);
                    } elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSRESOURCES_NORMAL, $normal_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSRESOURCES_VENDOR, $vendor_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSRESOURCES_DYNAMIC, $dynamic_resources);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLES_NORMAL, $normal_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLES_VENDOR, $vendor_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLES_DYNAMIC, $dynamic_bundles);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLEGROUPS_NORMAL, $normal_bundlegroups);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLEGROUPS_VENDOR, $vendor_bundlegroups);
                        $cachemanager->storeComponentModelCache($model_instance_id, POP_CACHETYPE_CSSBUNDLEGROUPS_DYNAMIC, $dynamic_bundlegroups);
                    }
                }
            }
        }

        $pack = array(
            'resources' => array(
                'all' => $resources,
                'by-subtype' => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_resources ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_resources ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_resources ?? array(),
                ),
            ),
            'bundles' => array(
                'all' => $bundles,
                'by-subtype' => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_bundles ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_bundles ?? array(),
                ),
            ),
            'bundlegroups' => array(
                'all' => $bundlegroups,
                'by-subtype' => array(
                    POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL => $normal_bundlegroups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR => $vendor_bundlegroups ?? array(),
                    POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC => $dynamic_bundlegroups ?? array(),
                ),
            ),
        );

        if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
            // Add the "template"
            $pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE] = $template_resources ?? array();
            $pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE] = $template_bundles ?? array();
            $pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE] = $template_bundlegroups ?? array();
        }

        return $pack;
    }

    public static function getJsResourcesPackByLoadingType($subtype, $model_instance_id = null) {

        // Check if the list of scripts has been cached in pop-cache/ first
        // If so, just return it from there directly
        global $pop_resourceloader_generatedfilesmanager, $pop_resourceloaderprocessor_manager;
        $memorymanager = TransientCacheManagerFacade::getInstance();

        if (!$model_instance_id) {
            $model_instance_id = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
        }

        $immediate_resources = $pop_resourceloader_generatedfilesmanager->getJsResourcesByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE);
        $async_resources = $pop_resourceloader_generatedfilesmanager->getJsResourcesByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC);
        $defer_resources = $pop_resourceloader_generatedfilesmanager->getJsResourcesByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER);

        // If there were resources in the cached file, then there will also be the corresponding bundles and bundlegroups
        if (!empty($immediate_resources) || !empty($async_resources) || !empty($defer_resources)) {

            $immediate_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIdsByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE);
            $async_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIdsByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC);
            $defer_bundles = $pop_resourceloader_generatedfilesmanager->getJsBundleIdsByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER);
            $immediate_bundleGroupId = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIdByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE);
            $async_bundleGroupId = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIdByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC);
            $defer_bundleGroupId = $pop_resourceloader_generatedfilesmanager->getJsBundlegroupIdByLoadingType($model_instance_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER);
        }
        else {
            // If there is no cached one, check if it was generated and cached on runtime
            $filename = $model_instance_id.'-'.$subtype;
            $immediate_resources = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSRESOURCES_IMMEDIATE);
            $async_resources = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSRESOURCES_ASYNC);
            $defer_resources = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSRESOURCES_DEFER);

            // If there is cached resources, there will also be bundles and bundlegroups
            if (!empty($immediate_resources) || !empty($async_resources) || !empty($defer_resources)) {

                $immediate_bundles = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLES_IMMEDIATE);
                $async_bundles = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLES_ASYNC);
                $defer_bundles = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLES_DEFER);
                $immediate_bundleGroupId = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLEGROUP_IMMEDIATE);
                $async_bundleGroupId = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLEGROUP_ASYNC);
                $defer_bundleGroupId = $memorymanager->getComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLEGROUP_DEFER);
            }
            // If there is no cached one, generate the resources and cache it
            else {

                $resources = self::calculateResources($model_instance_id);
                $resources = $pop_resourceloaderprocessor_manager->filterJs($resources);

                if ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL) {

                    $vendor_resources = $pop_resourceloaderprocessor_manager->filterVendor($resources);
                    $dynamic_resources = $pop_resourceloaderprocessor_manager->filterDynamic($resources);
                    $template_resources = $pop_resourceloaderprocessor_manager->filterTemplate($resources);
                    $resources = array_diff(
                        $resources,
                        $vendor_resources,
                        $dynamic_resources,
                        $template_resources
                    );
                }
                elseif ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR) {

                    $resources = $pop_resourceloaderprocessor_manager->filterVendor($resources);
                }
                elseif ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

                    $resources = $pop_resourceloaderprocessor_manager->filterDynamic($resources);
                }
                elseif ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE) {

                    $resources = $pop_resourceloaderprocessor_manager->filterTemplate($resources);
                }

                $resources_by_loading_type = self::splitJsResourcesByLoadingType($resources, $model_instance_id);
                $immediate_resources = $resources_by_loading_type['immediate']['resources'];
                $async_resources = $resources_by_loading_type['async']['resources'];
                $defer_resources = $resources_by_loading_type['defer']['resources'];
                $immediate_bundles = $resources_by_loading_type['immediate']['bundle-ids'];
                $async_bundles = $resources_by_loading_type['async']['bundle-ids'];
                $defer_bundles = $resources_by_loading_type['defer']['bundle-ids'];
                $immediate_bundleGroupId = $resources_by_loading_type['immediate']['bundlegroup'];
                $async_bundleGroupId = $resources_by_loading_type['async']['bundlegroup'];
                $defer_bundleGroupId = $resources_by_loading_type['defer']['bundlegroup'];

                // Save them in the pop-cache/
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSRESOURCES_IMMEDIATE, $immediate_resources);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSRESOURCES_ASYNC, $async_resources);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSRESOURCES_DEFER, $defer_resources);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLES_IMMEDIATE, $immediate_bundles);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLES_ASYNC, $async_bundles);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLES_DEFER, $defer_bundles);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLEGROUP_IMMEDIATE, $immediate_bundleGroupId);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLEGROUP_ASYNC, $async_bundleGroupId);
                $memorymanager->storeComponentModelCache($filename, POP_MEMORYTYPE_JSBUNDLEGROUP_DEFER, $defer_bundleGroupId);
            }
        }

        return array(
            'immediate' => array(
                'resources' => $immediate_resources,
                'bundles' => $immediate_bundles,
                'bundlegroup' => $immediate_bundleGroupId,
            ),
            'async' => array(
                'resources' => $async_resources,
                'bundles' => $async_bundles,
                'bundlegroup' => $async_bundleGroupId,
            ),
            'defer' => array(
                'resources' => $defer_resources,
                'bundles' => $defer_bundles,
                'bundlegroup' => $defer_bundleGroupId,
            ),
        );
    }

    public static function splitJsResourcesByLoadingType($resources, $model_instance_id = null) {

        // Generate the following files:
        // 1. Immediate, without defer or async scripts
        // 2. Defer
        // 3. Async
        global $pop_jsresourceloaderprocessor_manager;
        $async_resources = $pop_jsresourceloaderprocessor_manager->filterAsync($resources);
        $defer_resources = $pop_jsresourceloaderprocessor_manager->filterDefer($resources, $model_instance_id);

        // Only valid for Progressive Booting...
        if (PoP_WebPlatform_ServerUtils::useProgressiveBooting()) {

            // If these resources have been marked as 'noncritical', then defer loading them
            $memorymanager = TransientCacheManagerFacade::getInstance();
            if ($noncritical_resources = $memorymanager->getComponentModelCache($model_instance_id, POP_MEMORYTYPE_NONCRITICALRESOURCES)) {

                $defer_resources = array_values(
                    array_unique(
                        array_merge(
                            $defer_resources,
                            array_values(array_intersect($resources, $noncritical_resources))
                        ),
                        SORT_REGULAR
                    )
                );
            }
        }

        $immediate_resources = array_values(array_diff(
            $resources,
            $async_resources,
            $defer_resources
        ));

        $immediate_resourcebundles = $immediate_bundleids = $async_resourcebundles = $async_bundleids = $defer_resourcebundles = $defer_bundleids = array();
        if ($immediate_resources) {

            $immediate_resourcebundles = PoP_ResourceLoaderProcessorUtils::chunkResources($immediate_resources);
            $immediate_bundleids = array_map(array(self::class, 'getBundleId'), $immediate_resourcebundles);
            $immediate_bundlegroup = PoP_ResourceLoaderProcessorUtils::getBundlegroupId($immediate_resourcebundles, true);
        }
        if ($async_resources) {

            $async_resourcebundles = PoP_ResourceLoaderProcessorUtils::chunkResources($async_resources);
            $async_bundleids = array_map(array(self::class, 'getBundleId'), $async_resourcebundles);
            $async_bundlegroup = PoP_ResourceLoaderProcessorUtils::getBundlegroupId($async_resourcebundles, true);
        }
        if ($defer_resources) {

            $defer_resourcebundles = PoP_ResourceLoaderProcessorUtils::chunkResources($defer_resources);
            $defer_bundleids = array_map(array(self::class, 'getBundleId'), $defer_resourcebundles);
            $defer_bundlegroup = PoP_ResourceLoaderProcessorUtils::getBundlegroupId($defer_resourcebundles, true);
        }

        return array(
            'immediate' => array(
                'resources' => $immediate_resources,
                'bundles' => $immediate_resourcebundles,
                'bundle-ids' => $immediate_bundleids,
                'bundlegroup' => $immediate_bundlegroup,
            ),
            'async' => array(
                'resources' => $async_resources,
                'bundles' => $async_resourcebundles,
                'bundle-ids' => $async_bundleids,
                'bundlegroup' => $async_bundlegroup,
            ),
            'defer' => array(
                'resources' => $defer_resources,
                'bundles' => $defer_resourcebundles,
                'bundle-ids' => $defer_bundleids,
                'bundlegroup' => $defer_bundlegroup,
            ),
        );
    }

    public static function getBundleId($resources) {

        return PoP_ResourceLoaderProcessorUtils::getBundleId($resources, true);
    }

    protected static function calculateResources($model_instance_id, $options = array()) {

        // If first time accessing the function, calculate and then cache the resources, including both JS and CSS
        $key = $model_instance_id.json_encode($options);
        if (self::$calculated_resources[$key]) {

            return self::$calculated_resources[$key];
        }

        $engineState = App::getEngineState();

        // Comment Leo 20/10/2017: load always all the handlebars templates needed to render the page,
        // even if doing serverside-rendering so that we have already produced the HTML,
        // because components need initialization and they expect those templates loaded. Eg: Notifications,
        // which is a lazy-load. Additionally, we expect the next request to have so many templates in common,
        // so this acts as preloading those templates, making the 2nd request faster

        // We are given a toplevel. Iterate through all the pageSections, and obtain their resources
        $templateResources = $engineState->helperCalculations['template-resources'];

        // Add all the pageSection methods
        $data = $engineState->data;
        $pageSectionJSMethods = $data['componentsettings']['combinedstate']['jsmethods']['pagesection'];
        $blockJSMethods = $data['componentsettings']['combinedstate']['jsmethods']['block'];

        $methods = PoP_ResourceLoaderProcessorUtils::getJsmethods($pageSectionJSMethods, $blockJSMethods, true);
        $critical_methods = $methods[POP_PROGRESSIVEBOOTING_CRITICAL];
        $noncritical_methods = $methods[POP_PROGRESSIVEBOOTING_NONCRITICAL];

        // Get all the resources the template is dependent on. Eg: inline CSS styles
        // $modules_resources = array_values(array_unique(arrayFlatten(array_values($data['componentsettings']['combinedstate']['component-resources'] ?? array()))));
        $modules_resources = $engineState->helperCalculations['component-resources'];

        // Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
        self::$calculated_resources[$key] = PoP_ResourceLoaderProcessorUtils::calculateResources(true, $templateResources, $critical_methods, $noncritical_methods, $modules_resources, $model_instance_id, $options);
        return self::$calculated_resources[$key];
    }

    protected static function calculateBundles($resources, $addRandom) {

        $resources_set = PoP_ResourceLoaderProcessorUtils::chunkResources($resources);
        $bundle_ids = array_map(array(self::class, 'getBundleId'), $resources_set);
        $bundlegroup_ids = array(PoP_ResourceLoaderProcessorUtils::getBundlegroupId($bundle_ids, $addRandom));

        return array(
            'bundles' => $bundle_ids,
            'bundle-groups' => $bundlegroup_ids,
        );
    }

    public static function maybeGenerateBundlefiles($type, $enqueuefile_type, $bundlefiles) {

        if (PoP_ResourceLoader_ServerUtils::generateBundlefilesOnRuntime()) {

            // From the list of bundle(group) files, check which do not exist, and generate them
            // To know if they exist or not, simply check for their versions. If it has not been set, then the file does not exist
            global $pop_resourceloader_mappingstoragemanager;
            $versioned_bundlefiles = array();
            if ($enqueuefile_type == 'bundle') {

                $versioned_bundlefiles = array_keys($pop_resourceloader_mappingstoragemanager->getBundleVersions());
            }
            elseif ($enqueuefile_type == 'bundlegroup') {

                $versioned_bundlefiles = array_keys($pop_resourceloader_mappingstoragemanager->getBundlegroupVersions());
            }

            if ($bundlefiles_to_generate = array_diff(
                $bundlefiles,
                $versioned_bundlefiles
            )) {

                global $pop_resourceloader_currentroute_filegenerator_bundlefiles;
                $options = array(
                    'only-include' => array(
                        'enqueuefile-type' => $enqueuefile_type,
                        'ids' => $bundlefiles_to_generate,
                    ),
                    'generate-item-triggerhook' => true,
                );
                $pop_resourceloader_currentroute_filegenerator_bundlefiles->generate($options);

                // Trigger an action, to upload the files to S3
                \PoP\Root\App::doAction(
                    'PoPWebPlatform_ResourceLoader_ScriptsAndStylesUtils:generated-bundlefiles'
                );
            }
        }
    }

    public static function getDynamicModuleResourcesData() {

        // The dynamic resources will only be needed to optimize handlebars templates loading, when doing serverside-rendering and doing code-splitting
        if (defined('POP_SSR_INITIALIZED')) {
            if (!PoP_SSR_ServerUtils::disableServerSideRendering()) {
                if (PoP_ResourceLoader_ServerUtils::includeResourcesInBody()) {

                    // Lazy load the object
                    if (is_null(self::$dynamic_component_resources)) {

                        $cachemanager = null;
                        if ($useCache = ComponentModelModuleConfiguration::useComponentModelCache()) {
                            $cachemanager = PersistentCacheFacade::getInstance();
                        }

                        // Check if results are already on the cache
                        if ($useCache) {
                            self::$dynamic_component_resources = $cachemanager->getCacheByModelInstance(POP_CACHETYPE_DYNAMICCOMPONENTRESOURCES);
                        }
                        if (!self::$dynamic_component_resources) {

                            // If not, calculate the values now...
                            $engine = EngineFacade::getInstance();
                            $entryComponent = $engine->getEntryComponent();

                            // self::$dynamic_component_resources = $processorresourcedecorator->getDynamicModulesResources($entryComponent, $props);
                            global $pop_resourcemoduledecoratorprocessor_manager;
                            $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                            $processor = $componentprocessor_manager->getProcessor($entryComponent);
                            $processorresourcedecorator = $pop_resourcemoduledecoratorprocessor_manager->getProcessorDecorator($processor);
                            // @todo Check where $props comes from. Temporarily replaced with [] to avoid IDE error
                            self::$dynamic_component_resources = $processorresourcedecorator->getDynamicResourcesMergedmoduletree($entryComponent, []/*$props*/);

                            // And store them on the cache
                            if ($useCache) {
                                $cachemanager->storeCacheByModelInstance(POP_CACHETYPE_DYNAMICCOMPONENTRESOURCES, self::$dynamic_component_resources);
                            }
                        }
                    }
                }
            }
        }

        // Comment Leo 11/12/2017: get the dynamic templates resources, and print already their source and type, since
        // this information will be needed if including those resources in the body when initializing a lazy-load block
        if (self::$dynamic_component_resources) {

            global $pop_resourceloaderprocessor_manager;
            $dynamic_component_resourcesources = $dynamic_component_resourcetypes = array();
            foreach (self::$dynamic_component_resources as $resource) {

                // Source
                $resourceOutputName = ResourceUtils::getResourceOutputName($resource);
                $dynamic_component_resourcesources[$resourceOutputName] = $pop_resourceloaderprocessor_manager->getFileUrl($resource, true);

                // Type
                $resourcetype = $pop_resourceloaderprocessor_manager->getProcessor($resource)->getType($resource);
                $dynamic_component_resourcetypes[$resourcetype][] = $resourceOutputName;
            }

            return array(
                'sources' => $dynamic_component_resourcesources,
                'types' => $dynamic_component_resourcetypes,
            );
        }

        return array();
    }
}


