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

	// function get_bundle_id() {
	
	// 	return $this->bundle_id;
	// }
	function get_bundle_ids() {

		// if (!$this->bundle_ids && $this->resources) {

		// 	$resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources(array($this->resources));
		// 	$this->bundle_ids = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_bundle_id'), $resources_set);
		// }
	
		return $this->bundle_ids;
	}
	function get_bundlegroup_ids() {

		// if (!$this->bundlegroup_ids && $this->bundle_ids) {

		// 	$this->bundlegroup_ids = array(PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($this->bundle_ids));
		// }
	
		return $this->bundlegroup_ids;
	}

	function register_scripts() {

		// Check if the list of scripts has been cached in pop-cache/ first
		// If so, just return it from there directly
		global $gd_template_cachemanager;
        $engine = PoP_Engine_Factory::get_instance();
		$template_id = $engine->get_toplevel_template_id();

		if (!doing_post() && PoP_ServerUtils::use_cache()) {
			$resources = $gd_template_cachemanager->get_cache($template_id, POP_CACHETYPE_RESOURCES, true);
		}

		// If there is no cached one, generate the resources and cache it
		if (!$resources) {

			global $gd_template_processor_manager, $pop_resourceloaderprocessor_manager;

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

			// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
			$resources = PoP_ResourceLoaderProcessorUtils::calculate_resources($sources, $methods);

			// Save them in the pop-cache/
			if (!doing_post() && PoP_ServerUtils::use_cache()) {
				$gd_template_cachemanager->store_cache($template_id, POP_CACHETYPE_RESOURCES, $resources, true);
			}
		}

		$this->register_resources($resources);
	}

	function register_resources($resources) {

		// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		// $this->resources = array_unique(array_merge(
		// 	$this->resources,
		// 	$resources
		// ));
		$this->resources = $resources;
		$resources_set = PoP_ResourceLoaderProcessorUtils::chunk_resources(array($this->resources));
		$this->bundle_ids = array_map(array('PoP_ResourceLoaderProcessorUtils', 'get_bundle_id'), $resources_set);
		$this->bundlegroup_ids = array(PoP_ResourceLoaderProcessorUtils::get_bundlegroup_id($this->bundle_ids));

		// Enqueue the resources
		global $pop_resourceloaderprocessor_manager;
		$pop_resourceloaderprocessor_manager->enqueue_resources($this->resources);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $popfrontend_resourceloader_scriptsregistration;
$popfrontend_resourceloader_scriptsregistration = new PoPFrontend_ResourceLoader_ScriptsRegistration();

