<?php

class PoPFrontend_ResourceLoader_ScriptsAndStylesRegistration {

	var $resources, $bundle_ids, $bundlegroup_ids;
	protected $calculated_resources;

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

		// Check if the list of scripts has been cached in pop-cache/ first
		// If so, just return it from there directly
		global $gd_template_cachemanager, $gd_template_varshashprocessor_manager, $pop_resourceloader_generatedfilesmanager, $pop_resourceloaderprocessor_manager;
        $engine = PoP_Engine_Factory::get_instance();
		$template_id = $engine->get_toplevel_template_id();

		// First check if the resources have been cached from when executing /generate-theme/
		$processor = $gd_template_varshashprocessor_manager->get_processor($template_id);
		$vars_hash_id = $processor->get_vars_hash_id($template_id);

		if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

			$resources = $pop_resourceloader_generatedfilesmanager->get_js_resources($vars_hash_id);
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			$resources = $pop_resourceloader_generatedfilesmanager->get_css_resources($vars_hash_id);	
		}

		// If there were resources in the cached file, then there will also be the corresponding bundles and bundlegroups
		if ($resources) {

			if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

				$bundles = $pop_resourceloader_generatedfilesmanager->get_js_bundle_ids($vars_hash_id);
				$bundlegroups = $pop_resourceloader_generatedfilesmanager->get_js_bundlegroup_ids($vars_hash_id);
			}
			elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
				
				$bundles = $pop_resourceloader_generatedfilesmanager->get_css_bundle_ids($vars_hash_id);
				$bundlegroups = $pop_resourceloader_generatedfilesmanager->get_css_bundlegroup_ids($vars_hash_id);
			}
		}
		else {

			// If there is no cached one, check if it was generated and cached on runtime
			if (!doing_post() && PoP_ServerUtils::use_cache()) {

				if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {

					$resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES, true);
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

					$resources = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES, true);
				}
			}

			// If there is cached resources, there will also be bundles and bundlegroups
			if ($resources) {

				if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
					$bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES, true);
					$bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS, true);
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
					
					$bundles = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES, true);
					$bundlegroups = $gd_template_cachemanager->get_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS, true);
				}
			}
			// If there is no cached one, generate the resources and cache it
			else {

				// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
				$resources = $this->calculate_resources($vars_hash_id);

				// We have here both .cs and .jss resources. Split them into these, and calculate the bundle(group)s for each
				if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
					$resources = $pop_resourceloaderprocessor_manager->filter_js($resources);
				}
				elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
					
					$resources = $pop_resourceloaderprocessor_manager->filter_css($resources);
				}

				// Calculate the bundles and bundlegroups
				$generatedfiles = $this->calculate_bundles($resources);
				$bundles = $generatedfiles['bundles'];
				$bundlegroups = $generatedfiles['bundle-groups'];
		
				// Save them in the pop-cache/
				if (!doing_post() && PoP_ServerUtils::use_cache()) {

					if ($type == POP_RESOURCELOADER_RESOURCETYPE_JS) {
					
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSRESOURCES, $resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLES, $bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_JSBUNDLEGROUPS, $bundlegroups, true);
					}
					elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {
						
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSRESOURCES, $resources, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLES, $bundles, true);
						$gd_template_cachemanager->store_cache_by_template_id($template_id, POP_CACHETYPE_CSSBUNDLEGROUPS, $bundlegroups, true);
					}
				}
			}
		}

		$this->register_resources($type, $resources, $bundles, $bundlegroups/*, $remove_bundled_resources*/);
	}

	function register_resources($type, $resources, $bundles, $bundlegroups/*, $remove_bundled_resources = true*/) {

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
			$pop_jsresourceloaderprocessor_manager->enqueue_resources($resources, $bundles, $bundlegroups/*, $remove_bundled_resources*/);
			$pop_jsresourceloaderprocessor_manager->print_inline_resources($inline_resources);
		}
		elseif ($type == POP_RESOURCELOADER_RESOURCETYPE_CSS) {

			global $pop_cssresourceloaderprocessor_manager;
			$pop_cssresourceloaderprocessor_manager->enqueue_resources($resources, $bundles, $bundlegroups/*, $remove_bundled_resources*/);
			$pop_cssresourceloaderprocessor_manager->print_inline_resources($inline_resources);
		}
	}

	protected function calculate_resources($vars_hash_id) {

		// If first time accessing the function, calculate and then cache the resources, including both JS and CSS
		if ($this->calculated_resources) {

			return $this->calculated_resources;
		}

		$engine = PoP_Engine_Factory::get_instance();

		// Generate the $atts for this $vars
        $json = $engine->resultsObject['json'];

        // Comment Leo 20/10/2017: load always all the handlebars templates needed to render the page,
        // even if doing serverside-rendering so that we have already produced the HTML,
        // because components need initialization and they expect those templates loaded. Eg: Notifications,
        // which is a lazy-load. Additionally, we expect the next request to have so many templates in common,
        // so this acts as preloading those templates, making the 2nd request faster
        // $sources = array();
        // if (!PoP_Frontend_ServerUtils::use_serverside_rendering()) {
	        
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

	    $methods = PoP_ResourceLoaderProcessorUtils::get_jsmethods($pageSectionJSMethods, $blockJSMethods);
	    $critical_methods = $methods[POP_PROGRESSIVEBOOTING_CRITICAL];
	    $noncritical_methods = $methods[POP_PROGRESSIVEBOOTING_NONCRITICAL];

	    // Get all the resources the template is dependent on. Eg: inline CSS styles
	    $templates_resources = array_values(array_unique(array_flatten(array_values($json['sitemapping']['template-resources'] ?? array()))));

        // // Get the current vars_hash_id where to store $noncritical_resources
        // global $gd_template_varshashprocessor_manager;
        // $template_id = $engine->get_toplevel_template_id();
        // $vars_hash_id = $gd_template_varshashprocessor_manager->get_processor($template_id)->get_vars_hash_id($template_id);
        // $options = array(
        //     'vars_hash_id' => $vars_hash_id,
        // );

		// Get all the resources from the current request, from the loaded Handlebars templates and Javascript methods
		// return PoP_ResourceLoaderProcessorUtils::calculate_resources($sources, $methods);
		$this->calculated_resources = PoP_ResourceLoaderProcessorUtils::calculate_resources($sources, $critical_methods, $noncritical_methods, $templates_resources, $vars_hash_id/*, $options*/);

		return $this->calculated_resources;
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

