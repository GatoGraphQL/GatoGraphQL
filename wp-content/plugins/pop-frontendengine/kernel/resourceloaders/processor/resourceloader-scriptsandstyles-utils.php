<?php

class PoPFrontend_ResourceLoader_ScriptsAndStylesUtils {

	protected static $calculated_resources = array();

	public static function get_resources_pack($type, $vars_hash_id = null) {

		// Check if the list of scripts has been cached in pop-cache/ first
		// If so, just return it from there directly
		global $gd_template_cachemanager, $pop_resourceloader_generatedfilesmanager, $pop_resourceloaderprocessor_manager;
        if (!$vars_hash_id) {
	        $vars_hash_id = GD_Template_VarsHashProcessor_Utils::get_vars_hash_id();
	    }

		if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

			$vendor_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
			$normal_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
			$dynamic_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
			$template_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE);
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			$vendor_resources = $pop_resourceloader_generatedfilesmanager->get_css_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
			$normal_resources = $pop_resourceloader_generatedfilesmanager->get_css_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
			$dynamic_resources = $pop_resourceloader_generatedfilesmanager->get_css_resources($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
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

				$vendor_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
				$normal_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
				$dynamic_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
				$template_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE);
				$vendor_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
				$normal_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
				$dynamic_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
				$template_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE);
			}
			elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
				
				$vendor_bundles = $pop_resourceloader_generatedfilesmanager->get_css_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
				$normal_bundles = $pop_resourceloader_generatedfilesmanager->get_css_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
				$dynamic_bundles = $pop_resourceloader_generatedfilesmanager->get_css_bundle_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
				$vendor_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_css_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR);
				$normal_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_css_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL);
				$dynamic_bundlegroups = $pop_resourceloader_generatedfilesmanager->get_css_bundlegroup_ids($vars_hash_id, POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);
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
		}
		else {

			// If there is no cached one, check if it was generated and cached on runtime
			if (!doing_post() && PoP_ServerUtils::use_cache()) {

				if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

					$normal_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_NORMAL, true);
					$vendor_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_VENDOR, true);
					$dynamic_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_DYNAMIC, true);
					$template_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_TEMPLATE, true);
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

					$normal_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES_NORMAL, true);
					$vendor_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES_VENDOR, true);
					$dynamic_resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES_DYNAMIC, true);
				}

				// If the cache does not exists, each of these variables will be "false", and not an empty array, so the array_merge below would fail
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
					
					$normal_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_NORMAL, true);
					$vendor_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_VENDOR, true);
					$dynamic_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_DYNAMIC, true);
					$template_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_TEMPLATE, true);

					$normal_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_NORMAL, true);
					$vendor_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_VENDOR, true);
					$dynamic_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_DYNAMIC, true);
					$template_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_TEMPLATE, true);
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
					
					$normal_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES_NORMAL, true);
					$vendor_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES_VENDOR, true);
					$dynamic_bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES_DYNAMIC, true);

					$normal_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS_NORMAL, true);
					$vendor_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS_VENDOR, true);
					$dynamic_bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS_DYNAMIC, true);
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
			}
			// If there is no cached one, generate the resources and cache it
			else {

				// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
				$resources = self::calculate_resources($vars_hash_id);

				// We have here both .cs and .jss resources. Split them into these, and calculate the bundle(group)s for each
				if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
					$resources = $pop_resourceloaderprocessor_manager->filter_js($resources);
					$template_resources = $pop_resourceloaderprocessor_manager->filter_template($resources);
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
					
					$resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
				}

				$vendor_resources = $pop_resourceloaderprocessor_manager->filter_vendor($resources);
				$dynamic_resources = $pop_resourceloaderprocessor_manager->filter_dynamic($resources);
				$normal_resources = array_diff(
					$resources,
					$vendor_resources,
					$dynamic_resources,
					$template_resources ?? array()
				);

				// Calculate the bundles and bundlegroups
				$normal_generatedfiles = self::calculate_bundles($normal_resources, true);
				$vendor_generatedfiles = self::calculate_bundles($vendor_resources, true);
				$dynamic_generatedfiles = self::calculate_bundles($dynamic_resources, true);

				$vendor_bundles = $vendor_generatedfiles['bundles'];
				$normal_bundles = $normal_generatedfiles['bundles'];
				$dynamic_bundles = $dynamic_generatedfiles['bundles'];

				$vendor_bundlegroups = $vendor_generatedfiles['bundle-groups'];
				$normal_bundlegroups = $normal_generatedfiles['bundle-groups'];
				$dynamic_bundlegroups = $dynamic_generatedfiles['bundle-groups'];
				
				if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

					$template_generatedfiles = self::calculate_bundles($template_resources, true);
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
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
					
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
				if (!doing_post() && PoP_ServerUtils::use_cache()) {

					if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_NORMAL, $normal_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_VENDOR, $vendor_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_DYNAMIC, $dynamic_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES_TEMPLATE, $template_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_NORMAL, $normal_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_VENDOR, $vendor_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_DYNAMIC, $dynamic_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES_TEMPLATE, $template_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_NORMAL, $normal_bundlegroups, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_VENDOR, $vendor_bundlegroups, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_DYNAMIC, $dynamic_bundlegroups, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS_TEMPLATE, $template_bundlegroups, true);
					}
					elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
						
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES_NORMAL, $normal_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES_VENDOR, $vendor_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES_DYNAMIC, $dynamic_resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES_NORMAL, $normal_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES_VENDOR, $vendor_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES_DYNAMIC, $dynamic_bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS_NORMAL, $normal_bundlegroups, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS_VENDOR, $vendor_bundlegroups, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS_DYNAMIC, $dynamic_bundlegroups, true);
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

    public static function get_js_resources_pack_by_loading_type($subtype, $vars_hash_id = null) {

    	// Check if the list of scripts has been cached in pop-cache/ first
		// If so, just return it from there directly
		global $gd_template_memorymanager, $pop_resourceloader_generatedfilesmanager, $pop_resourceloaderprocessor_manager;
        if (!$vars_hash_id) {
	        $vars_hash_id = GD_Template_VarsHashProcessor_Utils::get_vars_hash_id();
	    }

		$immediate_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE);
		$async_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC);
		$defer_resources = $pop_resourceloader_generatedfilesmanager->get_js_resources_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER);

		// If there were resources in the cached file, then there will also be the corresponding bundles and bundlegroups
		if (!empty($immediate_resources) || !empty($async_resources) || !empty($defer_resources)) {

			$immediate_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE);
			$async_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC);
			$defer_bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER);
			$immediate_bundleGroupId = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_id_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_IMMEDIATE);
			$async_bundleGroupId = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_id_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_ASYNC);
			$defer_bundleGroupId = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_id_by_loading_type($vars_hash_id, $subtype, POP_RESOURCELOADER_LOADINGTYPE_DEFER);
		}
		else {
			// // If there is no cached one, check if it was generated and cached on runtime
			if (!doing_post() && PoP_ServerUtils::use_cache()) {

				$filename = $vars_hash_id.'-'.$subtype;

				$immediate_resources = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSRESOURCES_IMMEDIATE, true);
				$async_resources = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSRESOURCES_ASYNC, true);
				$defer_resources = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSRESOURCES_DEFER, true);
			}

			// If there is cached resources, there will also be bundles and bundlegroups
			if (!empty($immediate_resources) || !empty($async_resources) || !empty($defer_resources)) {

				$immediate_bundles = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSBUNDLES_IMMEDIATE, true);
				$async_bundles = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSBUNDLES_ASYNC, true);
				$defer_bundles = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSBUNDLES_DEFER, true);
				$immediate_bundleGroupId = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSBUNDLEGROUP_IMMEDIATE, true);
				$async_bundleGroupId = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSBUNDLEGROUP_ASYNC, true);
				$defer_bundleGroupId = $gd_template_memorymanager->get_cache($filename, POP_CACHETYPE_JSBUNDLEGROUP_DEFER, true);
			}
			// If there is no cached one, generate the resources and cache it
			else {

				$resources = self::calculate_resources($vars_hash_id);
				$resources = $pop_resourceloaderprocessor_manager->filter_js($resources);

				if ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL) {

					$vendor_resources = $pop_resourceloaderprocessor_manager->filter_vendor($resources);
					$dynamic_resources = $pop_resourceloaderprocessor_manager->filter_dynamic($resources);
					$template_resources = $pop_resourceloaderprocessor_manager->filter_template($resources);
					$resources = array_diff(
						$resources,
						$vendor_resources,
						$dynamic_resources,
						$template_resources
					);
				}
				elseif ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR) {

					$resources = $pop_resourceloaderprocessor_manager->filter_vendor($resources);
				}
				elseif ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC) {

					$resources = $pop_resourceloaderprocessor_manager->filter_dynamic($resources);
				}
				elseif ($subtype == POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE) {

					$resources = $pop_resourceloaderprocessor_manager->filter_template($resources);
				}
					
				$resources_by_loading_type = self::split_js_resources_by_loading_type($resources, $vars_hash_id);
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
				if (!doing_post() && PoP_ServerUtils::use_cache()) {
					
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSRESOURCES_IMMEDIATE, $immediate_resources, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSRESOURCES_ASYNC, $async_resources, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSRESOURCES_DEFER, $defer_resources, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSBUNDLES_IMMEDIATE, $immediate_bundles, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSBUNDLES_ASYNC, $async_bundles, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSBUNDLES_DEFER, $defer_bundles, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSBUNDLEGROUP_IMMEDIATE, $immediate_bundleGroupId, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSBUNDLEGROUP_ASYNC, $async_bundleGroupId, true);
					$gd_template_memorymanager->store_cache($filename, POP_CACHETYPE_JSBUNDLEGROUP_DEFER, $defer_bundleGroupId, true);
				}
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

    public static function split_js_resources_by_loading_type($resources, $vars_hash_id = null) {

    	// Generate the following files:
        // 1. Immediate, without defer or async scripts
        // 2. Defer
        // 3. Async
        global $pop_jsresourceloaderprocessor_manager;
        $async_resources = $pop_jsresourceloaderprocessor_manager->filter_async($resources);
        $defer_resources = $pop_jsresourceloaderprocessor_manager->filter_defer($resources, $vars_hash_id);
        
        // Only valid for Progressive Booting...
        if (PoP_Frontend_ServerUtils::use_progressive_booting()) {

            // If these resources have been marked as 'noncritical', then defer loading them
            global $gd_template_memorymanager;
            if ($noncritical_resources = $gd_template_memorymanager->get_cache($vars_hash_id, POP_MEMORYTYPE_NONCRITICALRESOURCES, true)) {

                $defer_resources = array_values(array_unique(array_merge(
                    $defer_resources,
                    array_intersect($resources, $noncritical_resources)
                )));
            }
        }

        $immediate_resources = array_values(array_diff(
            $resources,
            $async_resources,
            $defer_resources
        ));

        $immediate_resourcebundles = $immediate_bundleids = $async_resourcebundles = $async_bundleids = $defer_resourcebundles = $defer_bundleids = array();
        if ($immediate_resources) {

            $immediate_resourcebundles = PoP_ResourceLoaderProcessorUtils::chunk_resources($immediate_resources);
            $immediate_bundleids = array_map(array(self, 'get_bundle_id'), $immediate_resourcebundles);
            $immediate_bundlegroup = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($immediate_resourcebundles, true);
        }
        if ($async_resources) {

            $async_resourcebundles = PoP_ResourceLoaderProcessorUtils::chunk_resources($async_resources);
            $async_bundleids = array_map(array(self, 'get_bundle_id'), $async_resourcebundles);
            $async_bundlegroup = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($async_resourcebundles, true);
        }
        if ($defer_resources) {

            $defer_resourcebundles = PoP_ResourceLoaderProcessorUtils::chunk_resources($defer_resources);
            $defer_bundleids = array_map(array(self, 'get_bundle_id'), $defer_resourcebundles);
            $defer_bundlegroup = PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($defer_resourcebundles, true);
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

    function get_bundle_id($resources) {

    	return PoP_ResourceLoaderProcessorUtils::get_bundle_id($resources, true);
    }

	protected static function calculate_resources($vars_hash_id, $options = array()) {

		// If first time accessing the function, calculate and then cache the resources, including both JS and CSS
		$key = $vars_hash_id.json_encode($options);
		if (self::$calculated_resources[$key]) {

			return self::$calculated_resources[$key];
		}

		$engine = PoP_Engine_Factory::get_instance();

		// Generate the $atts for this $vars
        $json = $engine->resultsObject['json'];

        // Comment Leo 20/10/2017: load always all the handlebars templates needed to render the page,
        // even if doing serverside-rendering so that we have already produced the HTML,
        // because components need initialization and they expect those templates loaded. Eg: Notifications,
        // which is a lazy-load. Additionally, we expect the next request to have so many templates in common,
        // so this acts as preloading those templates, making the 2nd request faster

        // We are given a toplevel. Iterate through all the pageSections, and obtain their resources
        $template_sources = $template_extra_sources = array();
        if ($json['sitemapping']['template-sources']) {
	        
	        $template_sources = array_values(array_unique(array_values($json['sitemapping']['template-sources'])));
		}
		if ($json['sitemapping']['template-extra-sources']) {

	        $template_extra_sources = array_values(array_unique(array_flatten(array_values($json['sitemapping']['template-extra-sources']))));
	    }
	    $sources = array_unique(array_merge(
            $template_sources,
            $template_extra_sources
        ));

        // Add all the pageSection methods
        $pageSectionJSMethods = $json['settings']['jsmethods']['pagesection'];
	    $blockJSMethods = $json['settings']['jsmethods']['block'];

	    $methods = PoP_ResourceLoaderProcessorUtils::get_jsmethods($pageSectionJSMethods, $blockJSMethods, true);
	    $critical_methods = $methods[POP_PROGRESSIVEBOOTING_CRITICAL];
	    $noncritical_methods = $methods[POP_PROGRESSIVEBOOTING_NONCRITICAL];

	    // Get all the resources the template is dependent on. Eg: inline CSS styles
	    $templates_resources = array_values(array_unique(array_flatten(array_values($json['sitemapping']['template-resources'] ?? array()))));

        // Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		self::$calculated_resources[$key] = PoP_ResourceLoaderProcessorUtils::calculate_resources(false, $sources, $critical_methods, $noncritical_methods, $templates_resources, $vars_hash_id, $options);
		return self::$calculated_resources[$key];
	}

	protected static function calculate_bundles($resources, $addRandom) {

		$resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources($resources);
		$bundle_ids = array_map(array(self, 'get_bundle_id'), $resources_set);
		$bundlegroup_ids = array(PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($bundle_ids, $addRandom));

		return array(
			'bundles' => $bundle_ids,
			'bundle-groups' => $bundlegroup_ids,
		);
	}

    public static function get_loadingframe_resources($vars_hash_id = null) {

        // To obtain the list of all resources that are always loaded, we can simply calculate the resources for this actual request,
        // for page /generate-theme/ (POP_SYSTEM_PAGE_SYSTEM_GENERATETHEME), which because it doesn't add blocks or anything to the output,
        // it is strip of extra stuff, making it the minimum loading experience
        $js_loadingframe_resources_pack = self::get_resources_pack(POP_RESOURCELOADER_RESOURCETYPE_JS, $vars_hash_id);
        $css_loadingframe_resources_pack = self::get_resources_pack(POP_RESOURCELOADER_RESOURCETYPE_CSS, $vars_hash_id);
        return array_merge(
            $js_loadingframe_resources_pack['resources']['all'],
            $css_loadingframe_resources_pack['resources']['all']
        );
    }

    public static function maybe_generate_bundlefiles($type, $enqueuefile_type, $bundlefiles) {

		if (PoP_Frontend_ServerUtils::generate_bundlefiles_on_runtime()) {

			// From the list of bundle(group) files, check which do not exist, and generate them
			// To know if they exist or not, simply check for their versions. If it has not been set, then the file does not exist
			global $pop_resourceloader_mappingstoragemanager;
			$versioned_bundlefiles = array();
			if ($enqueuefile_type == 'bundle') {

				$versioned_bundlefiles = array_keys($pop_resourceloader_mappingstoragemanager->get_bundle_versions());
			}
			elseif ($enqueuefile_type == 'bundlegroup') {

				$versioned_bundlefiles = array_keys($pop_resourceloader_mappingstoragemanager->get_bundlegroup_versions());
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
				do_action(
					'PoPFrontend_ResourceLoader_ScriptsAndStylesUtils:generated-bundlefiles'
				);
			}
		}
	}
}


