<?php

class PoPFrontend_ResourceLoader_ScriptsAndStylesRegistration {

	var $resources, $bundle_ids, $bundlegroup_ids, $maybe_generated_bundlefiles;
	
	function __construct() {
	
		$this->resources = array(
			POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
			POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		);
		$this->bundle_ids = array(
			POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
			POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		);
		$this->bundlegroup_ids = array(
			POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
			POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		);
	}

	function get_resources() {
	
		return array_flatten(array_values($this->resources));
	}
	function get_bundle_ids() {

		return array_flatten(array_values($this->bundle_ids));
	}
	function get_bundlegroup_ids() {

		return array_flatten(array_values($this->bundlegroup_ids));
	}

	function register_scripts() {

		$this->register_scripts_or_styles(POP_RESOURCELOADER_RESOURCETYPE_JS);
	}

	function register_styles() {

		$this->register_scripts_or_styles(POP_RESOURCELOADER_RESOURCETYPE_CSS);
	}

	protected function register_scripts_or_styles($type) {

		global $popfrontend_resourceloader_scriptsandstyles_manager;
		$resources_pack = PoPFrontend_ResourceLoader_ScriptsAndStylesUtils::get_resources_pack($type);
		$this->register_resources($type, $resources_pack['resources']['all'], $resources_pack['bundles']['all'], $resources_pack['bundlegroups']['all'], false);

		// Maybe generate the bundlefiles on runtime
		$this->maybe_generate_bundlefiles($type, $resources_pack['bundles']['all'], $resources_pack['bundlegroups']['all']);

		if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

			// Enqueue the scripts in this order: 1. Vendor, 2. Normal, 3. Dynamic, 4. Template
			$subtypes = array(
				POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR,
				POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL,
				POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC,
				POP_RESOURCELOADER_RESOURCESUBTYPE_TEMPLATE,
			);
			foreach ($subtypes as $subtype) {

				$resources_pack_by_loading_type = PoPFrontend_ResourceLoader_ScriptsAndStylesUtils::get_js_resources_pack_by_loading_type($subtype);						
				$immediate_bundlegroups = $async_bundlegroups = $defer_bundlegroups = array();
				if ($immediate_bundleGroupId = $resources_pack_by_loading_type['immediate']['bundlegroup']) {
					$immediate_bundlegroups[] = $immediate_bundleGroupId;
				}
				if ($async_bundleGroupId = $resources_pack_by_loading_type['async']['bundlegroup']) {
					$async_bundlegroups[] = $async_bundleGroupId;
				}
				if ($defer_bundleGroupId = $resources_pack_by_loading_type['defer']['bundlegroup']) {
					$defer_bundlegroups[] = $defer_bundleGroupId;
				}
				
				$this->enqueue_scripts($subtype, $resources_pack_by_loading_type['immediate']['resources'], $resources_pack_by_loading_type['immediate']['bundles'], $immediate_bundlegroups);
				$this->enqueue_scripts($subtype, $resources_pack_by_loading_type['async']['resources'], $resources_pack_by_loading_type['async']['bundles'], $async_bundlegroups, 'async="async"');
				$this->enqueue_scripts($subtype, $resources_pack_by_loading_type['defer']['resources'], $resources_pack_by_loading_type['defer']['bundles'], $defer_bundlegroups, 'defer="defer"');
			}
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			$this->enqueue_styles(POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR, $resources_pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR], $resources_pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR], $resources_pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_VENDOR]);
			$this->enqueue_styles(POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL, $resources_pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL], $resources_pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL], $resources_pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_NORMAL]);
			$this->enqueue_styles(POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC, $resources_pack['resources']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC], $resources_pack['bundles']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC], $resources_pack['bundlegroups']['by-subtype'][POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC]);
		}
	}

	function register_resources($type, $resources, $bundles, $bundlegroups) {

		$this->resources[$type] = $resources;
		$this->bundle_ids[$type] = $bundles;
		$this->bundlegroup_ids[$type] = $bundlegroups;

		// Comment Leo 21/11/2017: Set the resources on $popResourceLoader, so that CSS resources have their URL to print it in the body
		$this->init_resourceloader($type, $resources);

		global $pop_resourceloaderprocessor_manager;
		$inline_resources = $pop_resourceloaderprocessor_manager->filter_inline($resources);

		// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
			// Enqueue the resources
			global $pop_jsresourceloaderprocessor_manager;
			$pop_jsresourceloaderprocessor_manager->print_inline_resources($inline_resources);
			// $pop_jsresourceloaderprocessor_manager->enqueue_resources($resources, $bundles, $bundlegroups);
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			global $pop_cssresourceloaderprocessor_manager;
			$pop_cssresourceloaderprocessor_manager->print_inline_resources($inline_resources);
			// $pop_cssresourceloaderprocessor_manager->enqueue_resources($resources, $bundles, $bundlegroups);
		}
	}

	protected function maybe_generate_bundlefiles($type, $bundles, $bundlegroups) {

		// Maybe generate the bundlefiles on runtime
		if (PoP_Frontend_ServerUtils::loading_bundlefile()) {

			$enqueuefile_type = PoP_Frontend_ServerUtils::get_enqueuefile_type();
			$bundlefiles = array();
			if ($enqueuefile_type == 'bundle') {

				$bundlefiles = $bundles;
			}
			elseif ($enqueuefile_type == 'bundlegroup') {
				
				$bundlefiles = $bundlegroups;
			}

			PoPFrontend_ResourceLoader_ScriptsAndStylesUtils::maybe_generate_bundlefiles($type, $enqueuefile_type, $bundlefiles);
		}
	}

	function enqueue_scripts($subtype, $resources, $bundles, $bundlegroups, $attr = '') {

		$across_thememodes = ($subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);

		global $pop_jsresourceloaderprocessor_manager;
		$pop_jsresourceloaderprocessor_manager->enqueue_scripts($across_thememodes, $resources, $bundles, $bundlegroups, $attr);
	}

	function enqueue_styles($subtype, $resources, $bundles, $bundlegroups) {

		$across_thememodes = ($subtype != POP_RESOURCELOADER_RESOURCESUBTYPE_DYNAMIC);

		global $pop_cssresourceloaderprocessor_manager;
		$pop_cssresourceloaderprocessor_manager->enqueue_styles($across_thememodes, $resources, $bundles, $bundlegroups);
	}

	protected function init_resourceloader($type, $resources) {

		// Comment Leo 21/11/2017: set the resources into the $popResourceLoader instance
		global $pop_resourceloaderprocessor_manager;
		$sources = array();
		$types = array(
            $type => array(),
		);
		foreach ($resources as $resource) {

			$sources[$resource] = $pop_resourceloaderprocessor_manager->get_file_url($resource, true);
			$types[$type][] = $resource;
		}
		// $in_body = $pop_resourceloaderprocessor_manager->filter_in_body($resources);

		// Do a merge below, because this function will be invoked twice: once for the JS and once for the CSS, so don't let them override each other
		$domain = get_site_url();
		$popResourceLoader = PoP_ServerSide_Libraries_Factory::get_resourceloader_instance();
		if (!$popResourceLoader->config[$domain]) {

			$popResourceLoader->config[$domain] = array(
				'resources' => array(),
				'sources' => array(),
				'types' => array(
		            POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
		            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
		        ),
		        // 'in-body' => array(),
			);
		}
		$popResourceLoader->config[$domain]['resources'] = array_merge(
			$popResourceLoader->config[$domain]['resources'],
			$resources
		);
		$popResourceLoader->config[$domain]['sources'] = array_merge(
			$popResourceLoader->config[$domain]['sources'],
			$sources
		);
		$popResourceLoader->config[$domain]['types'][$type] = array_merge(
			$popResourceLoader->config[$domain]['types'][$type],
			$types[$type]
		);
		// $popResourceLoader->config[$domain]['in-body'] = array_merge(
		// 	$popResourceLoader->config[$domain]['in-body'],
		// 	$in_body
		// );
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $popfrontend_resourceloader_scriptsandstyles_registration;
$popfrontend_resourceloader_scriptsandstyles_registration = new PoPFrontend_ResourceLoader_ScriptsAndStylesRegistration();

