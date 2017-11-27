<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ResourceLoaderProcessor_Manager {

	var $processors;
	
	function __construct() {
	
		$this->processors = array();
	}
	
	function get_processor($resource) {

		$processor = $this->processors[$resource];
		if (!$processor) {
			throw new Exception(sprintf('No Processor for $resource \'%s\' (%s)', $resource, full_url()));
		}
	
		return $processor;
	}
	
	function get_processors() {

		return $this->processors;
	}
	
	function get_resources() {

		// Return a list of all created resources
		return array_keys($this->processors);
	}
	
	function add($processor, $resources_to_process) {
	
		foreach ($resources_to_process as $resource) {
		
			$this->processors[$resource] = $processor;
		}	
	}

	function get_file_url($resource, $add_version = false) {

		$url = $this->get_processor($resource)->get_file_url($resource);
		if ($add_version) {
			
			// External files do not have a $version defined (since it's already harcoded in the file path)
			// Whenever there is no version defined, WordPress will add the WP version in `function do_item`, 
			// in file wp-includes/class.wp-scripts.php
			// called from adding our scripts through `wp_enqueue_script`
			// So then we must get that version here, so that it will always match
			$version = $this->get_processor($resource)->get_version($resource);
			if (!$version) {
				$version = get_bloginfo( 'version' );
			}
			$url = add_query_arg('ver', $version, $url);
		}
		return $url;
	}

	function get_type($resource) {

		return $this->get_processor($resource)->get_type($resource);
	}

	function get_file_path($resource) {

		return $this->get_processor($resource)->get_file_path($resource);
	}

	function get_asset_path($resource) {

		return $this->get_processor($resource)->get_asset_path($resource);
	}

	function get_enqueuable_resources($resources) {

		// We can only enqueue the resources that do NOT go in the body or are inlined. 
		// Those ones will be added when doing $popResourceLoader->includeResources (in the body), or hardcoded (inline, such as utils-inline.js)
		if ($in_body_resources = $this->filter_in_body($resources)) {

			$resources = array_diff(
				$resources,
				$in_body_resources
			);
		}
		if ($inline_resources = $this->filter_inline($resources)) {

			$resources = array_diff(
				$resources,
				$inline_resources
			);
		}

		return $resources;
	}

	function filter_js($resources) {

		return array_filter($resources, array($this, 'is_js'));
	}

	function is_js($resource) {

		return $this->get_processor($resource)->get_type($resource) == POP_RESOURCELOADER_RESOURCETYPE_JS;
	}

	function filter_css($resources) {

		return array_filter($resources, array($this, 'is_css'));
	}

	function is_css($resource) {

		return $this->get_processor($resource)->get_type($resource) == POP_RESOURCELOADER_RESOURCETYPE_CSS;
	}

	function filter_in_body($resources) {

		if (PoP_Frontend_ServerUtils::include_resources_in_body()) {

			// Extract all the resources added through PoP_Processor->get_template_resources($template_id, $atts)
			$engine = PoP_Engine_Factory::get_instance();
			$json = $engine->resultsObject['json'];
			if ($templates_resources = $json['sitemapping']['template-resources']) {
				
				$templates_resources = array_values(array_unique(array_flatten(array_values($templates_resources))));
				return array_intersect($resources, $templates_resources);
			}
		}

		return array();

		// return array_filter($resources, array($this, 'in_body'));
	}

	// function in_body($resource) {

	// 	return $this->get_processor($resource)->in_body($resource);
	// }

	function filter_inline($resources) {

		return array_filter($resources, array($this, 'inline'));
	}

	function inline($resource) {

		return $this->get_processor($resource)->inline($resource);
	}

	function filter_can_bundle($resources) {

		// Remove all resources which go in the body, those cannot be bundled
		if ($in_body_resources = $this->filter_in_body($resources)) {
			
			$resources = array_diff(
				$resources,
				$in_body_resources
			);
		}
		if ($inline_resources = $this->filter_inline($resources)) {
			
			$resources = array_diff(
				$resources,
				$inline_resources
			);
		}
		
		return array_filter($resources, array($this, 'can_bundle'));
	}

	function can_bundle($resource) {

		return $this->get_processor($resource)->can_bundle($resource);
	}

	function add_resource(&$resources, $resource/*, $resource_key*/) {

		// Enqueue the resource
		// if (!in_array($resource, $this->enqueued_resources)) {
		if (!in_array($resource, $resources)) {
		// if (!in_array($resource, array_flatten(array_values($resources)))) {

			// // Say that no need to add this resource
			// $this->enqueued_resources[] = $resource;

			// $resources[$resource_key][] = $resource;
			$resources[] = $resource;
		}
	}

	function add_resource_dependencies(&$resources, $resource, $add_resource/*, $resource_key = ''*/) {

		// // Say that no need to add this resource
		// $this->enqueued_resources[] = $resource;

		$processor = $this->get_processor($resource);

		// First enqueue the dependencies, then continue to enqueue the needed resources
		$dependencies = $processor->get_dependencies($resource);
		// foreach ($dependencies as $dependency_resource/* => $dependency_resource_methods*/) {
		// 	$this->add_resources_from_jsobjects($resources, $dependency_resource/*, $dependency_resource_methods*/);
		// }
		foreach ($dependencies as $dependency_resource/* => $dependency_resource_methods*/) {
			
			// if (!in_array($dependency_resource, $this->enqueued_resources)) {
			if (!in_array($dependency_resource, $resources)) {

				$this->add_resource_dependencies($resources, $dependency_resource, true/*, 'external'*/);
			}
		}
	
		// Enqueue the resource, at the end, after its dependencies have been added
		if ($add_resource) {
			$this->add_resource($resources, $resource/*, $resource_key*/);
		}
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_resourceloaderprocessor_manager;
$pop_resourceloaderprocessor_manager = new PoP_ResourceLoaderProcessor_Manager();
