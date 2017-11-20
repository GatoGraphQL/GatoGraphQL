<?php

class PoPFrontend_ResourceLoader_ScriptsRegistration {

	var $resources/*, $bundle_id*/, $bundle_ids;

	function __construct() {
	
		$this->resources = array();
		$this->bundle_ids = array();
		$this->bundlegroup_ids = array();
	}

	function get_resources() {
	
		return $this->resources;
	}

	function get_bundle_ids() {

		return $this->bundle_ids;
	}
	function get_bundlegroup_ids() {

		return $this->bundlegroup_ids;
	}

	function register_scripts() {

		// Check if the list of scripts has been cached in pop-cache/ first
		// If so, just return it from there directly
		global $gd_template_cachemanager, $gd_template_cacheprocessor_manager, $pop_resourceloader_generatedfilesstoragemanager;
        $engine = PoP_Engine_Factory::get_instance();
		$template_id = $engine->get_toplevel_template_id();

		// First check if the resources have been cached from when executing /generate-theme/
		$processor = $gd_template_cacheprocessor_manager->get_processor($template_id);
		$cachename = $processor->get_cache_filename($template_id);
		$resources = $pop_resourceloader_generatedfilesstoragemanager->get_resources($cachename);
		$bundles = $bundlegroups = array();
		// If there were resources in the cached file, then there will also be the corresponding bundles and bundlegroups

		if ($resources) {

			$bundles = $pop_resourceloader_generatedfilesstoragemanager->get_bundle_ids($cachename);
			$bundlegroups = $pop_resourceloader_generatedfilesstoragemanager->get_bundlegroup_ids($cachename);
		}
		else {

		// If there is no cached one, check if it was generated and cached on runtime
		// if (!$resources) {

			if (!doing_post() && PoP_ServerUtils::use_cache()) {
				$resources = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_RESOURCES, true);
			}

			// If there is cached resources, there will also be bundles and bundlegroups
			if ($resources) {

				$bundles = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_BUNDLES, true);
				$bundlegroups = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_BUNDLEGROUPS, true);
			}
			// If there is no cached one, generate the resources and cache it
			// if (!$resources) {
			else {

				// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
				$resources = $this->calculate_resources();

				// Calculate the bundles and bundlegroups
				$generatedfiles = $this->calculate_bundles($resources);
				$bundles = $generatedfiles['bundles'];
				$bundlegroups = $generatedfiles['bundle-groups'];
		
				// Save them in the pop-cache/
				if (!doing_post() && PoP_ServerUtils::use_cache()) {

					$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_RESOURCES, $resources, true);
					$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_BUNDLES, $bundles, true);
					$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_BUNDLEGROUPS, $bundlegroups, true);
				}
			}
		}

		// $remove_bundled_resources = true;
		$this->register_resources($resources, $bundles, $bundlegroups/*, $remove_bundled_resources*/);
	}

	protected function calculate_resources() {

		$engine = PoP_Engine_Factory::get_instance();

		// Generate the $atts for this $vars
        // $json = json_decode($engine->json['json'], true);
        // $json = json_decode($engine->json['encoded-json'], true);
        $json = $engine->resultsObject['json'];

        // Comment Leo 20/10/2017: load always all the handlebars templates needed to render the page,
        // even if doing serverside-rendering so that we have already produced the HTML,
        // because components need initialization and they expect those templates loaded. Eg: Notifications,
        // which is a lazy-load. Additionally, we expect the next request to have so many templates in common,
        // so this acts as preloading those templates, making the 2nd request faster
        // $sources = array();
        // if (!PoP_Frontend_ServerUtils::use_serverside_rendering()) {
	        
        // We are given a toplevel. Iterate through all the pageSections, and obtain their resources
        $template_sources = array_values(array_unique(array_values($json['sitemapping']['template-sources'])));
        $template_extra_sources = array_values(array_unique(array_flatten(array_values($json['sitemapping']['template-extra-sources']))));
        $sources = array_unique(array_merge(
            $template_sources,
            $template_extra_sources
        ));
	    // }

        // Add all the pageSection methods
        $pageSectionJSMethods = $json['settings']['jsmethods']['pagesection'];
	    $blockJSMethods = $json['settings']['jsmethods']['block'];

	    $methods = PoP_ResourceLoaderProcessorUtils::get_jsmethods($pageSectionJSMethods, $blockJSMethods);
	    $critical_methods = $methods[POP_PROGRESSIVEBOOTING_CRITICAL];
	    $noncritical_methods = $methods[POP_PROGRESSIVEBOOTING_NONCRITICAL];

		// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		// return PoP_ResourceLoaderProcessorUtils::calculate_resources($sources, $methods);
		return PoP_ResourceLoaderProcessorUtils::calculate_resources($sources, $critical_methods, $noncritical_methods);
	}

	function calculate_bundles($resources) {

		$resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources($resources);
		$bundle_ids = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_bundle_id'), $resources_set);
		$bundlegroup_ids = array(PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($bundle_ids));

		return array(
			'bundles' => $bundle_ids,
			'bundle-groups' => $bundlegroup_ids,
		);
	}

	function register_resources($resources, $bundles, $bundlegroups/*, $remove_bundled_resources = true*/) {

		// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		$this->resources = $resources;
		$this->bundle_ids = $bundles;
		$this->bundlegroup_ids = $bundlegroups;

		// Enqueue the resources
		global $pop_resourceloaderprocessor_manager;
		$pop_resourceloaderprocessor_manager->enqueue_resources($this->bundlegroup_ids, $this->bundle_ids, $this->resources/*, $remove_bundled_resources*/);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $popfrontend_resourceloader_scriptsregistration;
$popfrontend_resourceloader_scriptsregistration = new PoPFrontend_ResourceLoader_ScriptsRegistration();

